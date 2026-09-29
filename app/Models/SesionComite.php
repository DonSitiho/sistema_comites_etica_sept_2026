<?php

namespace App\Models;

use App\Enums\EstadoSesion;
use Illuminate\Database\Eloquent\Model;

class SesionComite extends Model
{
    protected $table = 'sesiones_comite';

    protected $fillable = [
        'comite_id',
        'plan_anual_id',
        'ejercicio',
        'trimestre',
        'fecha_sesion',
        'fecha_limite',
        'comentarios_enlace',
    ];

    /*
     * Deliberadamente NO aparecen:
     *
     * estado
     * fecha_bloqueada_en
     * fecha_validacion
     * revisado_por
     *
     * porque deben modificarse mediante
     * la lógica de negocio.
     */

    protected $casts = [
        'ejercicio' => 'integer',
        'trimestre' => 'integer',

        'fecha_sesion' => 'date',
        'fecha_limite' => 'date',

        'estado' =>
            EstadoSesion::class,

        'fecha_bloqueada_en' =>
            'datetime',

        'fecha_envio' =>
            'datetime',

        'fecha_inicio_revision' =>
            'datetime',

        'fecha_observacion' =>
            'datetime',

        'fecha_validacion' =>
            'datetime',

        'version' => 'integer',
    ];

    public function comite()
    {
        return $this->belongsTo(
            Comite::class,
            'comite_id'
        );
    }

    public function planAnual()
    {
        return $this->belongsTo(
            PlanAnual::class,
            'plan_anual_id'
        );
    }

    public function documentos()
    {
        return $this->hasMany(
            Documento::class,
            'sesion_comite_id'
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

    public function puedeEditar(): bool
    {
        return in_array(
            $this->estado,
            [
                EstadoSesion::PENDIENTE,
                EstadoSesion::CON_OBSERVACIONES,
            ],
            true
        );
    }

    public function fechaEstaBloqueada(): bool
    {
        return $this->fecha_bloqueada_en !== null;
    }

    public function estaValidada(): bool
    {
        return $this->estado ===
            EstadoSesion::VALIDADA;
    }

    public function scopeDelEjercicio(
        $query,
        int $ejercicio
    ) {
        return $query->where(
            'ejercicio',
            $ejercicio
        );
    }

    public function scopeDelTrimestre(
        $query,
        int $trimestre
    ) {
        return $query->where(
            'trimestre',
            $trimestre
        );
    }
}