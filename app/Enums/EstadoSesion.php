<?php

namespace App\Enums;

enum EstadoSesion: string
{
    case PENDIENTE =
        'pendiente';

    case CARGADA =
        'cargada';

    case EN_REVISION =
        'en_revision';

    case CON_OBSERVACIONES =
        'con_observaciones';

    case VALIDADA =
        'validada';

    public function etiqueta(): string
    {
        return match ($this) {
            self::PENDIENTE =>
                'Pendiente',

            self::CARGADA =>
                'Cargada',

            self::EN_REVISION =>
                'En revisión',

            self::CON_OBSERVACIONES =>
                'Con observaciones',

            self::VALIDADA =>
                'Validada',
        };
    }

    public function claseBootstrap(): string
    {
        return match ($this) {
            self::PENDIENTE =>
                'secondary',

            self::CARGADA =>
                'primary',

            self::EN_REVISION =>
                'info',

            self::CON_OBSERVACIONES =>
                'warning',

            self::VALIDADA =>
                'success',
        };
    }
}