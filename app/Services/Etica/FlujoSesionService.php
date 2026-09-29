<?php

namespace App\Services\Etica;

use App\Enums\EstadoSesion;
use App\Models\HistorialEstado;
use App\Models\SesionComite;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FlujoSesionService
{
    /**
     * El Enlace envía la sesión a SECOEM.
     */
    public function enviar(
        SesionComite $sesion,
        User $usuario
    ): SesionComite {
        return DB::transaction(
            function () use (
                $sesion,
                $usuario
            ) {
                /*
                 * Bloqueamos temporalmente el registro
                 * mientras se ejecuta esta operación.
                 *
                 * Esto evita modificaciones simultáneas.
                 */
                $sesion = SesionComite::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $sesion->id
                    );

                $estadosPermitidos = [
                    EstadoSesion::PENDIENTE,
                    EstadoSesion::CON_OBSERVACIONES,
                ];

                if (!in_array(
                    $sesion->estado,
                    $estadosPermitidos,
                    true
                )) {
                    throw ValidationException::withMessages([
                        'estado' =>
                            'La sesión no puede enviarse desde su estado actual.',
                    ]);
                }

                /*
                 * No se permite enviar una sesión
                 * sin fecha.
                 */
                if (!$sesion->fecha_sesion) {
                    throw ValidationException::withMessages([
                        'fecha_sesion' =>
                            'Debe registrar la fecha de la sesión antes de enviarla.',
                    ]);
                }

                /*
                 * Comprobamos documentos obligatorios.
                 */
                $this->validarDocumentosObligatorios(
                    $sesion
                );

                $estadoAnterior =
                    $sesion->estado;

                /*
                 * IMPORTANTE:
                 *
                 * Usamos forceFill() porque estos campos
                 * deliberadamente NO se encuentran en
                 * $fillable del modelo.
                 *
                 * Eso evita que puedan alterarse
                 * directamente desde un formulario.
                 */
                $datos = [
                    'estado' =>
                        EstadoSesion::CARGADA,

                    'fecha_envio' =>
                        now(),

                    'version' =>
                        $sesion->version + 1,
                ];

                /*
                 * La primera vez que se envía,
                 * bloqueamos permanentemente la fecha.
                 */
                if (
                    !$sesion->fecha_bloqueada_en
                ) {
                    $datos['fecha_bloqueada_en'] =
                        now();
                }

                $sesion
                    ->forceFill($datos)
                    ->save();

                $this->registrarHistorial(
                    $sesion,
                    $estadoAnterior,
                    EstadoSesion::CARGADA,
                    $usuario
                );

                return $sesion->fresh();
            }
        );
    }

    /**
     * El Evaluador inicia la revisión.
     */
    public function iniciarRevision(
        SesionComite $sesion,
        User $usuario
    ): SesionComite {
        return $this->cambiarEstado(
            sesion: $sesion,

            esperado:
                EstadoSesion::CARGADA,

            nuevo:
                EstadoSesion::EN_REVISION,

            usuario:
                $usuario,

            datosAdicionales: [
                'revisado_por' =>
                    $usuario->id,

                'fecha_inicio_revision' =>
                    now(),
            ],
        );
    }

    /**
     * El Evaluador genera observaciones.
     */
    public function observar(
        SesionComite $sesion,
        User $usuario,
        string $observacion
    ): SesionComite {
        return $this->cambiarEstado(
            sesion: $sesion,

            esperado:
                EstadoSesion::EN_REVISION,

            nuevo:
                EstadoSesion::CON_OBSERVACIONES,

            usuario:
                $usuario,

            datosAdicionales: [
                'fecha_observacion' =>
                    now(),
            ],

            motivo:
                $observacion,
        );
    }

    /**
     * El Evaluador valida definitivamente.
     */
    public function validar(
        SesionComite $sesion,
        User $usuario
    ): SesionComite {
        return $this->cambiarEstado(
            sesion: $sesion,

            esperado:
                EstadoSesion::EN_REVISION,

            nuevo:
                EstadoSesion::VALIDADA,

            usuario:
                $usuario,

            datosAdicionales: [
                'fecha_validacion' =>
                    now(),
            ],
        );
    }

    /**
     * Método interno para realizar
     * transiciones de estado.
     */
    private function cambiarEstado(
        SesionComite $sesion,
        EstadoSesion $esperado,
        EstadoSesion $nuevo,
        User $usuario,
        array $datosAdicionales = [],
        ?string $motivo = null
    ): SesionComite {
        return DB::transaction(
            function () use (
                $sesion,
                $esperado,
                $nuevo,
                $usuario,
                $datosAdicionales,
                $motivo
            ) {
                $sesion =
                    SesionComite::query()
                        ->lockForUpdate()
                        ->findOrFail(
                            $sesion->id
                        );

                /*
                 * Comprobamos nuevamente el estado
                 * dentro de la transacción.
                 */
                if (
                    $sesion->estado !==
                    $esperado
                ) {
                    abort(
                        409,
                        'La sesión cambió de estado mientras se procesaba la operación.'
                    );
                }

                $sesion
                    ->forceFill([
                        ...$datosAdicionales,

                        'estado' =>
                            $nuevo,

                        'version' =>
                            $sesion->version + 1,
                    ])
                    ->save();

                $this->registrarHistorial(
                    $sesion,
                    $esperado,
                    $nuevo,
                    $usuario,
                    $motivo
                );

                return $sesion->fresh();
            }
        );
    }

    /**
     * Comprueba que se hayan cargado
     * todos los documentos obligatorios.
     */
    private function validarDocumentosObligatorios(
        SesionComite $sesion
    ): void {
        $tiposObligatorios =
            TipoDocumento::query()
                ->where(
                    'aplica_sesion',
                    true
                )
                ->where(
                    'obligatorio',
                    true
                )
                ->where(
                    'activo',
                    true
                )
                ->get();

        $faltantes = [];

        foreach (
            $tiposObligatorios
            as $tipo
        ) {
            $existe =
                $sesion
                    ->documentos()
                    ->where(
                        'tipo_documento_id',
                        $tipo->id
                    )
                    ->where(
                        'es_actual',
                        true
                    )
                    ->exists();

            if (!$existe) {
                $faltantes[] =
                    $tipo->nombre;
            }
        }

        if (!empty($faltantes)) {
            throw ValidationException::withMessages([
                'documentos' =>
                    'Faltan los siguientes documentos obligatorios: '
                    .implode(
                        ', ',
                        $faltantes
                    ),
            ]);
        }
    }

    /**
     * Registra la trazabilidad del cambio.
     */
    private function registrarHistorial(
        SesionComite $sesion,
        ?EstadoSesion $anterior,
        EstadoSesion $nuevo,
        User $usuario,
        ?string $motivo = null
    ): void {
        HistorialEstado::create([
            'entidad_tipo' =>
                SesionComite::class,

            'entidad_id' =>
                $sesion->id,

            'estado_anterior' =>
                $anterior?->value,

            'estado_nuevo' =>
                $nuevo->value,

            'motivo' =>
                $motivo,

            'cambiado_por' =>
                $usuario->id,

            'direccion_ip' =>
                request()->ip(),

            'agente_usuario' =>
                request()->userAgent(),

            'fecha_cambio' =>
                now(),
        ]);
    }
}