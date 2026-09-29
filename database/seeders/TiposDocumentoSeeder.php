<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TiposDocumentoSeeder extends Seeder
{
    /**
     * Catálogo inicial de tipos de documentos.
     */
    public function run(): void
    {
        $tipos = [
            [
                'clave' => 'PLAN_ANUAL',
                'nombre' => 'Plan Anual de Trabajo',
                'descripcion' =>
                    'Documento correspondiente al Plan Anual '
                    .'de Trabajo del Comité de Ética.',

                'aplica_plan_anual' => true,
                'aplica_sesion' => false,
                'obligatorio' => true,
                'orden' => 1,
                'activo' => true,
            ],

            [
                'clave' => 'ACTA_SESION',
                'nombre' => 'Acta de sesión',
                'descripcion' =>
                    'Acta correspondiente a la sesión '
                    .'trimestral del Comité de Ética.',

                'aplica_plan_anual' => false,
                'aplica_sesion' => true,
                'obligatorio' => true,
                'orden' => 1,
                'activo' => true,
            ],

            [
                'clave' => 'LISTA_ASISTENCIA',
                'nombre' => 'Lista de asistencia',
                'descripcion' =>
                    'Lista de asistencia de las personas '
                    .'participantes en la sesión.',

                'aplica_plan_anual' => false,
                'aplica_sesion' => true,
                'obligatorio' => true,
                'orden' => 2,
                'activo' => true,
            ],

            [
                'clave' => 'EVIDENCIA_ACUERDOS',
                'nombre' =>
                    'Evidencia de cumplimiento de acuerdos',

                'descripcion' =>
                    'Documentación utilizada para acreditar '
                    .'el cumplimiento de acuerdos derivados '
                    .'de la sesión.',

                'aplica_plan_anual' => false,
                'aplica_sesion' => true,
                'obligatorio' => true,
                'orden' => 3,
                'activo' => true,
            ],

            [
                'clave' => 'OTRO',
                'nombre' => 'Documento adicional',
                'descripcion' =>
                    'Documento complementario proporcionado '
                    .'por el Comité de Ética.',

                'aplica_plan_anual' => true,
                'aplica_sesion' => true,
                'obligatorio' => false,
                'orden' => 99,
                'activo' => true,
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoDocumento::updateOrCreate(
                [
                    'clave' => $tipo['clave'],
                ],
                $tipo
            );
        }
    }
}