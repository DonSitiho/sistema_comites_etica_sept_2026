<?php

namespace App\Enums;

enum SemaforoGestion: string
{
    case VERDE = 'verde';
    case AMARILLO = 'amarillo';
    case ROJO = 'rojo';

    public function etiqueta(): string
    {
        return match ($this) {
            self::VERDE => 'Verde',
            self::AMARILLO => 'Amarillo',
            self::ROJO => 'Rojo',
        };
    }

    public function claseBootstrap(): string
    {
        return match ($this) {
            self::VERDE => 'success',
            self::AMARILLO => 'warning',
            self::ROJO => 'danger',
        };
    }
}