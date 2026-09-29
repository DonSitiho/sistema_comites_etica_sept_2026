<?php

namespace App\Http\Controllers\Etica;

use App\Http\Controllers\Controller;
use App\Http\Requests\Etica\ActualizarSesionRequest;
use App\Http\Requests\Etica\EnviarSesionRequest;
use App\Http\Requests\Etica\ObservarSesionRequest;
use App\Http\Requests\Etica\ValidarSesionRequest;
use App\Models\SesionComite;
use App\Services\Etica\FlujoSesionService;
use Illuminate\Http\RedirectResponse;

class SesionComiteController extends Controller
{
    /**
     * Actualiza datos editables
     * de la sesión.
     */
    public function actualizar(
        ActualizarSesionRequest $request,
        SesionComite $sesion
    ): RedirectResponse {
        /*
         * El FormRequest ya realizó
         * la autorización.
         */
        $sesion->update(
            $request->validated()
        );

        return back()->with(
            'success',
            'La sesión se actualizó correctamente.'
        );
    }

    /**
     * El Enlace entrega la sesión
     * para revisión.
     */
    public function enviar(
        EnviarSesionRequest $request,
        SesionComite $sesion,
        FlujoSesionService $servicio
    ): RedirectResponse {
        $servicio->enviar(
            $sesion,
            $request->user()
        );

        return back()->with(
            'success',
            'La sesión fue enviada correctamente.'
        );
    }

    /**
     * El Evaluador comienza a revisar.
     */
    public function iniciarRevision(
        SesionComite $sesion,
        FlujoSesionService $servicio
    ): RedirectResponse {
        $this->authorize(
            'revisar',
            $sesion
        );

        $servicio->iniciarRevision(
            $sesion,
            auth()->user()
        );

        return back()->with(
            'success',
            'La sesión se encuentra ahora en revisión.'
        );
    }

    /**
     * El Evaluador devuelve la sesión
     * con observaciones.
     */
    public function observar(
        ObservarSesionRequest $request,
        SesionComite $sesion,
        FlujoSesionService $servicio
    ): RedirectResponse {
        $servicio->observar(
            $sesion,
            $request->user(),
            $request->validated(
                'observacion'
            )
        );

        return back()->with(
            'success',
            'Las observaciones fueron registradas correctamente.'
        );
    }

    /**
     * Valida definitivamente
     * la sesión.
     */
    public function validar(
        ValidarSesionRequest $request,
        SesionComite $sesion,
        FlujoSesionService $servicio
    ): RedirectResponse {
        $servicio->validar(
            $sesion,
            $request->user()
        );

        return back()->with(
            'success',
            'La sesión fue validada correctamente.'
        );
    }
}