<?php

namespace App\Models;

use App\Enums\EstadoEvaluacion;
use App\Enums\SemaforoGestion;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';

    protected $fillable = [
        'comite_id',
        'ejercicio',
        'evaluador_id',
        'observaciones_generales',
    ];

    protected $casts = [
        'ejercicio' => 'integer',

        'puntaje_cumplimiento' =>
            'decimal:2',

        'puntaje_desempeno' =>
            'decimal:2',

        'calificacion_final' =>
            'decimal:2',

        'estado' =>
            EstadoEvaluacion::class,

        'semaforo' =>
            SemaforoGestion::class,

        'fecha_evaluacion' =>
            'datetime',

        'fecha_finalizacion' =>
            'datetime',

        'fecha_emision' =>
            'datetime',
    ];

    public function comite()
    {
        return $this->belongsTo(
            Comite::class,
            'comite_id'
        );
    }

    public function evaluador()
    {
        return $this->belongsTo(
            User::class,
            'evaluador_id'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            DetalleEvaluacion::class,
            'evaluacion_id'
        );
    }

    public function cedula()
    {
        return $this->hasOne(
            CedulaEvaluacion::class,
            'evaluacion_id'
        );
    }
}