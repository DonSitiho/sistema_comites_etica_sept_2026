<?php

namespace App\Policies;

use App\Enums\EstadoSesion;
use App\Models\SesionComite;
use App\Models\User;

class SesionComitePolicy
{
    /**
     * Puede consultar la sesión.
     */
    public function view(
        User $usuario,
        SesionComite $sesion
    ): bool {
        /*
         * Evaluadores y Operación Técnica
         * pueden consultar todas.
         */
        if (
            $usuario->can('sesiones.revisar') ||
            $usuario->can('operacion.supervisar')
        ) {
            return true;
        }

        /*
         * Un Enlace solamente puede consultar
         * sesiones de sus propios Comités.
         */
        return $usuario
            ->comites()
            ->whereKey(
                $sesion->comite_id
            )
            ->exists();
    }

    /**
     * Puede editar una sesión.
     */
    public function update(
        User $usuario,
        SesionComite $sesion
    ): bool {
        if (
            !$usuario->can('sesiones.editar')
        ) {
            return false;
        }

        if (
            !$this->perteneceAlComite(
                $usuario,
                $sesion
            )
        ) {
            return false;
        }

        return in_array(
            $sesion->estado,
            [
                EstadoSesion::PENDIENTE,
                EstadoSesion::CON_OBSERVACIONES,
            ],
            true
        );
    }

    /**
     * Puede enviar una sesión.
     */
    public function enviar(
        User $usuario,
        SesionComite $sesion
    ): bool {
        if (
            !$usuario->can('sesiones.enviar')
        ) {
            return false;
        }

        if (
            !$this->perteneceAlComite(
                $usuario,
                $sesion
            )
        ) {
            return false;
        }

        return in_array(
            $sesion->estado,
            [
                EstadoSesion::PENDIENTE,
                EstadoSesion::CON_OBSERVACIONES,
            ],
            true
        );
    }

    /**
     * Puede iniciar la revisión.
     */
    public function revisar(
        User $usuario,
        SesionComite $sesion
    ): bool {
        return
            $usuario->can('sesiones.revisar')
            &&
            $sesion->estado ===
                EstadoSesion::CARGADA;
    }

    /**
     * Puede generar observaciones.
     */
    public function observar(
        User $usuario,
        SesionComite $sesion
    ): bool {
        return
            $usuario->can('sesiones.observar')
            &&
            $sesion->estado ===
                EstadoSesion::EN_REVISION;
    }

    /**
     * Puede validar definitivamente.
     */
    public function validar(
        User $usuario,
        SesionComite $sesion
    ): bool {
        return
            $usuario->can('sesiones.validar')
            &&
            $sesion->estado ===
                EstadoSesion::EN_REVISION;
    }

    /**
     * Verifica si un usuario pertenece
     * al Comité de la sesión.
     */
    private function perteneceAlComite(
        User $usuario,
        SesionComite $sesion
    ): bool {
        return $usuario
            ->comites()
            ->whereKey(
                $sesion->comite_id
            )
            ->exists();
    }
}