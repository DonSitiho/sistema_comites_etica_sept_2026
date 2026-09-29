<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de usuarios de prueba.
     */
    public function run(): void
    {
        /*
         * =====================================================
         * OPERACIÓN TÉCNICA
         * =====================================================
         */

        $operacion = User::updateOrCreate(
            [
                'email' => 'admin@secoem.michoacan.gob.mx',
            ],
            [
                'name' => 'Operación Técnica',
                'password' => Hash::make('Prueba0000!'),
                'email_verified_at' => now(),
            ]
        );

        $operacion->syncRoles([
            'Operación Técnica',
        ]);

        /*
         * =====================================================
         * ENLACE DEL COMITÉ
         * =====================================================
         */

        $enlace = User::updateOrCreate(
            [
                'email' => 'enlace@secoem.michoacan.gob.mx',
            ],
            [
                'name' => 'Enlace del Comité',
                'password' => Hash::make('Prueba0000!'),
                'email_verified_at' => now(),
            ]
        );

        $enlace->syncRoles([
            'Enlace del Comité',
        ]);

        /*
         * =====================================================
         * EVALUADOR SECOEM
         * =====================================================
         */

        $evaluador = User::updateOrCreate(
            [
                'email' => 'evaluador@secoem.michoacan.gob.mx',
            ],
            [
                'name' => 'Evaluador SECOEM',
                'password' => Hash::make('Prueba0000!'),
                'email_verified_at' => now(),
            ]
        );

        $evaluador->syncRoles([
            'Evaluador SECOEM',
        ]);
    }
}