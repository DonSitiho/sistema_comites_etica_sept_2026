<?php

namespace App\Models;

use App\Enums\EstadoPlanAnual;
use Illuminate\Database\Eloquent\Model;

class PlanAnual extends Model
{
    protected $table = 'planes_anuales';

    protected $fillable = [
        'comite_id',
        'ejercicio',
        'observaciones',
    ];

    protected $casts = [
        'ejercicio' => 'integer',

        'estado' =>
            EstadoPlanAnual::class,

        'fecha_envio' =>
            'datetime',

        'fecha_inicio_revision' =>
            'datetime',

        'fecha_observacion' =>
            'datetime',

        'fecha_validacion' =>
            'datetime',
    ];

    public function comite()
    {
        return $this->belongsTo(
            Comite::class,
            'comite_id'
        );
    }

    public function sesiones()
    {
        return $this->hasMany(
            SesionComite::class,
            'plan_anual_id'
        );
    }

    public function documentos()
    {
        return $this->hasMany(
            Documento::class,
            'plan_anual_id'
        );
    }

    public function creador()
    {
        return $this->belongsTo(
            User::class,
            'creado_por'
        );
    }

    public function revisor()
    {
        return $this->belongsTo(
            User::class,
            'revisado_por'
        );
    }
}