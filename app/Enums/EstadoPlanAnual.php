<?php

namespace App\Enums;

enum EstadoPlanAnual: string
{
    case PENDIENTE = 'pendiente';
    case CARGADO = 'cargado';
    case EN_REVISION = 'en_revision';
    case CON_OBSERVACIONES = 'con_observaciones';
    case VALIDADO = 'validado';

    public function etiqueta(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente',
            self::CARGADO => 'Cargado',
            self::EN_REVISION => 'En revisión',
            self::CON_OBSERVACIONES => 'Con observaciones',
            self::VALIDADO => 'Validado',
        };
    }
}