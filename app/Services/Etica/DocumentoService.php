<?php

namespace App\Services\Etica;

use App\Models\Documento;
use App\Models\SesionComite;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class DocumentoService
{
    /**
     * Carga o sustituye un documento
     * correspondiente a una sesión.
     */
    public function cargarParaSesion(
        SesionComite $sesion,
        UploadedFile $archivo,
        int $tipoDocumentoId,
        int $usuarioId
    ): Documento {
        /*
         * El hash se calcula mientras todavía
         * tenemos el archivo temporal.
         */
        $hash = hash_file(
            'sha256',
            $archivo->getRealPath()
        );

        $nombreAlmacenado =
            Str::uuid()->toString()
            .'.pdf';

        $directorio = sprintf(
            'private/etica/%d/comite-%d/trimestre-%d',
            $sesion->ejercicio,
            $sesion->comite_id,
            $sesion->trimestre
        );

        /*
         * Guardamos el archivo en storage/app.
         */
        $ruta = $archivo->storeAs(
            $directorio,
            $nombreAlmacenado,
            'local'
        );

        if (!$ruta) {
            throw new \RuntimeException(
                'No fue posible almacenar el archivo.'
            );
        }

        try {
            return DB::transaction(
                function () use (
                    $sesion,
                    $archivo,
                    $tipoDocumentoId,
                    $usuarioId,
                    $nombreAlmacenado,
                    $ruta,
                    $hash
                ) {
                    $sesion =
                        SesionComite::query()
                            ->lockForUpdate()
                            ->findOrFail(
                                $sesion->id
                            );

                    if (
                        !$sesion->puedeEditar()
                    ) {
                        abort(
                            409,
                            'La sesión no permite modificar documentos.'
                        );
                    }

                    /*
                     * Buscamos la versión actual
                     * del mismo tipo de documento.
                     */
                    $anterior =
                        Documento::query()
                            ->where(
                                'sesion_comite_id',
                                $sesion->id
                            )
                            ->where(
                                'tipo_documento_id',
                                $tipoDocumentoId
                            )
                            ->where(
                                'es_actual',
                                true
                            )
                            ->lockForUpdate()
                            ->first();

                    $version =
                        ($anterior?->version ?? 0)
                        + 1;

                    /*
                     * La versión anterior permanece
                     * almacenada, pero deja de ser actual.
                     */
                    if ($anterior) {
                        $anterior->update([
                            'es_actual' => false,
                        ]);
                    }

                    return Documento::create([
                        'tipo_documento_id' =>
                            $tipoDocumentoId,

                        'sesion_comite_id' =>
                            $sesion->id,

                        'nombre_original' =>
                            $archivo
                                ->getClientOriginalName(),

                        'nombre_almacenado' =>
                            $nombreAlmacenado,

                        'ruta' =>
                            $ruta,

                        'tipo_mime' =>
                            $archivo->getMimeType(),

                        'tamano' =>
                            $archivo->getSize(),

                        'hash_sha256' =>
                            $hash,

                        'version' =>
                            $version,

                        'es_actual' =>
                            true,

                        'documento_anterior_id' =>
                            $anterior?->id,

                        'cargado_por' =>
                            $usuarioId,

                        'fecha_carga' =>
                            now(),
                    ]);
                }
            );
        } catch (Throwable $e) {

            /*
             * Si falla la BD, eliminamos el archivo
             * recién almacenado para no dejar basura.
             */
            Storage::disk('local')
                ->delete($ruta);

            throw $e;
        }
    }
}