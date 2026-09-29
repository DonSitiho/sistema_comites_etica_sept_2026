<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CedulaEvaluacion extends Model
{
    protected $table =
        'cedulas_evaluacion';

    protected $fillable = [
        'evaluacion_id',
        'folio',
        'ruta',
        'hash_sha256',
        'fecha_emision',
        'emitida_por',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
    ];

    public function evaluacion()
    {
        return $this->belongsTo(
            Evaluacion::class,
            'evaluacion_id'
        );
    }

    public function emisor()
    {
        return $this->belongsTo(
            User::class,
            'emitida_por'
        );
    }
}