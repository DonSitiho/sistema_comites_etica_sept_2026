<?php

namespace App\Enums;

enum EstadoEvaluacion: string
{
    case BORRADOR = 'borrador';
    case EN_PROCESO = 'en_proceso';
    case FINALIZADA = 'finalizada';
    case EMITIDA = 'emitida';

    public function etiqueta(): string
    {
        return match ($this) {
            self::BORRADOR => 'Borrador',
            self::EN_PROCESO => 'En proceso',
            self::FINALIZADA => 'Finalizada',
            self::EMITIDA => 'Emitida',
        };
    }
}