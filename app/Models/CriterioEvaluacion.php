<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriterioEvaluacion extends Model
{
    protected $table =
        'criterios_evaluacion';

    protected $fillable = [
        'ejercicio',
        'clave',
        'nombre',
        'descripcion',
        'puntaje_maximo',
        'orden',
        'activo',
    ];

    protected $casts = [
        'ejercicio' => 'integer',
        'puntaje_maximo' => 'decimal:2',
        'orden' => 'integer',
        'activo' => 'boolean',
    ];

    public function detalles()
    {
        return $this->hasMany(
            DetalleEvaluacion::class,
            'criterio_evaluacion_id'
        );
    }
}