<?php

namespace App\Services\Etica;

use App\Enums\EstadoSesion;
use App\Enums\SemaforoGestion;
use App\Models\Evaluacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EvaluacionService
{
    private const MAXIMO_CUMPLIMIENTO =
        25.00;

    private const MAXIMO_DESEMPENO =
        75.00;

    /**
     * Calcula los componentes 25/75.
     */
    public function calcular(
        Evaluacion $evaluacion
    ): array {
        $cumplimiento =
            $this->calcularCumplimiento(
                $evaluacion
            );

        $desempeno =
            $this->calcularDesempeno(
                $evaluacion
            );

        $final = round(
            $cumplimiento + $desempeno,
            2
        );

        return [
            'puntaje_cumplimiento' =>
                $cumplimiento,

            'puntaje_desempeno' =>
                $desempeno,

            'calificacion_final' =>
                $final,

            'semaforo' =>
                $this->obtenerSemaforo(
                    $final
                ),
        ];
    }

    /**
     * Calcula los 25 puntos automáticos.
     */
    private function calcularCumplimiento(
        Evaluacion $evaluacion
    ): float {
        $sesionesValidadas =
            $evaluacion
                ->comite
                ->sesiones()
                ->where(
                    'ejercicio',
                    $evaluacion->ejercicio
                )
                ->where(
                    'estado',
                    EstadoSesion::VALIDADA->value
                )
                ->count();

        $sesionesValidadas =
            min(
                $sesionesValidadas,
                4
            );

        return round(
            (
                $sesionesValidadas / 4
            )
            *
            self::MAXIMO_CUMPLIMIENTO,
            2
        );
    }

    /**
     * Calcula los 75 puntos evaluados
     * manualmente por SECOEM.
     */
    private function calcularDesempeno(
        Evaluacion $evaluacion
    ): float {
        $puntaje =
            (float)
            $evaluacion
                ->detalles()
                ->sum(
                    'puntaje_obtenido'
                );

        if (
            $puntaje >
            self::MAXIMO_DESEMPENO
        ) {
            throw ValidationException::withMessages([
                'puntaje_desempeno' =>
                    'El componente de desempeño no puede superar 75 puntos.',
            ]);
        }

        return round(
            $puntaje,
            2
        );
    }

    /**
     * Determina el semáforo.
     */
    private function obtenerSemaforo(
        float $calificacion
    ): SemaforoGestion {
        $minimoVerde =
            (float) config(
                'etica.semaforo.verde_minimo'
            );

        $minimoAmarillo =
            (float) config(
                'etica.semaforo.amarillo_minimo'
            );

        return match (true) {
            $calificacion >=
                $minimoVerde =>
                    SemaforoGestion::VERDE,

            $calificacion >=
                $minimoAmarillo =>
                    SemaforoGestion::AMARILLO,

            default =>
                SemaforoGestion::ROJO,
        };
    }

    /**
     * Guarda los cálculos.
     */
    public function guardarCalculo(
        Evaluacion $evaluacion
    ): Evaluacion {
        return DB::transaction(
            function () use (
                $evaluacion
            ) {
                $resultado =
                    $this->calcular(
                        $evaluacion
                    );

                /*
                 * forceFill porque las calificaciones
                 * no deben ser modificables desde
                 * cualquier formulario.
                 */
                $evaluacion
                    ->forceFill([
                        'puntaje_cumplimiento' =>
                            $resultado[
                                'puntaje_cumplimiento'
                            ],

                        'puntaje_desempeno' =>
                            $resultado[
                                'puntaje_desempeno'
                            ],

                        'calificacion_final' =>
                            $resultado[
                                'calificacion_final'
                            ],

                        'semaforo' =>
                            $resultado[
                                'semaforo'
                            ],

                        'fecha_evaluacion' =>
                            now(),
                    ])
                    ->save();

                return $evaluacion->fresh();
            }
        );
    }
}