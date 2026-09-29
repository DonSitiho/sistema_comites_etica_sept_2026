<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comite extends Model
{
    protected $table = 'comites';

    protected $fillable = [
        'ente_id',
        'nombre',
        'fecha_instalacion',
        'activo',
    ];

    protected $casts = [
        'fecha_instalacion' => 'date',
        'activo' => 'boolean',
    ];

    public function ente(): BelongsTo
    {
        return $this->belongsTo(
            Ente::class,
            'ente_id'
        );
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'comite_user',
            'comite_id',
            'user_id'
        )->withTimestamps();
    }

    public function planesAnuales(): HasMany
    {
        return $this->hasMany(
            PlanAnual::class,
            'comite_id'
        );
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(
            SesionComite::class,
            'comite_id'
        );
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(
            Evaluacion::class,
            'comite_id'
        );
    }
}