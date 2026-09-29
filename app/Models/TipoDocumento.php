<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tipos_documento';

    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'aplica_plan_anual',
        'aplica_sesion',
        'obligatorio',
        'orden',
        'activo',
    ];

    protected $casts = [
        'aplica_plan_anual' => 'boolean',
        'aplica_sesion' => 'boolean',
        'obligatorio' => 'boolean',
        'activo' => 'boolean',
    ];

    public function documentos()
    {
        return $this->hasMany(
            Documento::class,
            'tipo_documento_id'
        );
    }
}