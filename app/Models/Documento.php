<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'tipo_documento_id',
        'plan_anual_id',
        'sesion_comite_id',

        'nombre_original',
        'nombre_almacenado',
        'ruta',

        'tipo_mime',
        'tamano',
        'hash_sha256',

        'version',
        'es_actual',

        'documento_anterior_id',

        'cargado_por',
        'fecha_carga',
    ];

    protected $casts = [
        'tamano' => 'integer',
        'version' => 'integer',
        'es_actual' => 'boolean',
        'fecha_carga' => 'datetime',
    ];

    public function tipoDocumento()
    {
        return $this->belongsTo(
            TipoDocumento::class,
            'tipo_documento_id'
        );
    }

    public function planAnual()
    {
        return $this->belongsTo(
            PlanAnual::class,
            'plan_anual_id'
        );
    }

    public function sesion()
    {
        return $this->belongsTo(
            SesionComite::class,
            'sesion_comite_id'
        );
    }

    public function usuarioCarga()
    {
        return $this->belongsTo(
            User::class,
            'cargado_por'
        );
    }

    public function documentoAnterior()
    {
        return $this->belongsTo(
            Documento::class,
            'documento_anterior_id'
        );
    }

    public function nuevaVersion()
    {
        return $this->hasOne(
            Documento::class,
            'documento_anterior_id'
        );
    }
}