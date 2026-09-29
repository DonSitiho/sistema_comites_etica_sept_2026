<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ente extends Model
{
    protected $table = 'entes';

    protected $fillable = [
        'clave',
        'nombre',
        'siglas',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function comite(): HasOne
    {
        return $this->hasOne(
            Comite::class,
            'ente_id'
        );
    }
}