<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Alimenta la base de datos inicial del sistema.
     */
    public function run(): void
    {
        $this->call([
            /*
             * 1. Primero se crean roles y permisos.
             *
             * Esto DEBE ejecutarse antes de UsersSeeder,
             * porque los usuarios necesitan que los roles
             * ya existan.
             */
            RolesPermissionsSeeder::class,

            /*
             * 2. Después se crean los usuarios y
             * se les asignan los roles correspondientes.
             */
            UsersSeeder::class,

            /*
             * 3. Finalmente cargamos los catálogos
             * iniciales del sistema.
             */
            TiposDocumentoSeeder::class,

            /*
             * Más adelante:
             */
            // CriteriosEvaluacionSeeder::class,
        ]);
    }
}