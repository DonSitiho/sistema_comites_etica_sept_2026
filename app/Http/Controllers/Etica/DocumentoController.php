<?php

namespace App\Http\Controllers\Etica;

use App\Http\Controllers\Controller;
use App\Http\Requests\Etica\CargarDocumentoRequest;
use App\Models\Documento;
use App\Models\SesionComite;
use App\Services\Etica\DocumentoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    /**
     * Guarda un documento de sesión.
     */
    public function guardar(
        CargarDocumentoRequest $request,
        SesionComite $sesion,
        DocumentoService $servicio
    ): RedirectResponse {
        /*
         * Además del permiso genérico,
         * comprobamos que pueda editar
         * esta sesión concreta.
         */
        $this->authorize(
            'update',
            $sesion
        );

        $servicio->cargarParaSesion(
            sesion: $sesion,

            archivo:
                $request->file(
                    'archivo'
                ),

            tipoDocumentoId:
                $request->integer(
                    'tipo_documento_id'
                ),

            usuarioId:
                $request->user()->id,
        );

        return back()->with(
            'success',
            'El documento fue cargado correctamente.'
        );
    }

    /**
     * Descarga segura.
     */
    public function descargar(
        Documento $documento
    ) {
        /*
         * Más adelante tendremos
         * DocumentoPolicy.
         */
        abort_unless(
            auth()->user()
                ->can('documentos.descargar'),
            403
        );

        abort_unless(
            Storage::disk('local')
                ->exists(
                    $documento->ruta
                ),
            404,
            'El archivo no existe.'
        );

        return Storage::disk('local')
            ->download(
                $documento->ruta,
                $documento->nombre_original
            );
    }
}