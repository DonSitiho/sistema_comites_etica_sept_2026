<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEvaluacion extends Model
{
    protected $table =
        'detalles_evaluacion';

    protected $fillable = [
        'evaluacion_id',
        'criterio_evaluacion_id',
        'puntaje_obtenido',
        'observaciones',
    ];

    protected $casts = [
        'puntaje_obtenido' => 'decimal:2',
    ];

    public function evaluacion()
    {
        return $this->belongsTo(
            Evaluacion::class,
            'evaluacion_id'
        );
    }

    public function criterio()
    {
        return $this->belongsTo(
            CriterioEvaluacion::class,
            'criterio_evaluacion_id'
        );
    }
}