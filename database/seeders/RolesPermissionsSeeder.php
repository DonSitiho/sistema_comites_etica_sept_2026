<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de roles y permisos.
     */
    public function run(): void
    {
        /*
         * Limpiar la caché de permisos de Spatie.
         *
         * Es importante cuando se modifican permisos
         * o roles mediante seeders.
         */
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        /*
         * =====================================================
         * PERMISOS DEL SISTEMA
         * =====================================================
         */

        $permisos = [
            /*
             * Tablero
             */
            'tablero.ver',

            /*
             * Entes
             */
            'entes.ver',
            'entes.crear',
            'entes.editar',
            'entes.eliminar',

            /*
             * Comités
             */
            'comites.ver',
            'comites.crear',
            'comites.editar',
            'comites.eliminar',

            /*
             * Plan Anual
             */
            'planes-anuales.ver',
            'planes-anuales.crear',
            'planes-anuales.editar',
            'planes-anuales.cargar',
            'planes-anuales.enviar',
            'planes-anuales.revisar',
            'planes-anuales.observar',
            'planes-anuales.validar',

            /*
             * Sesiones trimestrales
             */
            'sesiones.ver',
            'sesiones.crear',
            'sesiones.editar',
            'sesiones.enviar',
            'sesiones.revisar',
            'sesiones.observar',
            'sesiones.validar',

            /*
             * Documentos
             */
            'documentos.ver',
            'documentos.cargar',
            'documentos.descargar',

            /*
             * Evaluaciones
             */
            'evaluaciones.ver',
            'evaluaciones.crear',
            'evaluaciones.calificar',
            'evaluaciones.finalizar',

            /*
             * Cédulas
             */
            'cedulas.ver',
            'cedulas.emitir',
            'cedulas.descargar',

            /*
             * Reportes
             */
            'reportes.ver',
            'reportes.generar',
            'reportes.exportar',

            /*
             * Catálogos
             */
            'catalogos.ver',
            'catalogos.crear',
            'catalogos.editar',
            'catalogos.eliminar',

            /*
             * Usuarios
             */
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'usuarios.asignar-roles',

            /*
             * Operación técnica
             */
            'operacion.supervisar',
            'operacion.ver-auditoria',
        ];

        /*
         * =====================================================
         * CREAR PERMISOS
         * =====================================================
         */

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => $permiso,
                'guard_name' => 'web',
            ]);
        }

        /*
         * =====================================================
         * CREAR ROLES
         * =====================================================
         */

        $rolEnlace = Role::firstOrCreate([
            'name' => 'Enlace del Comité',
            'guard_name' => 'web',
        ]);

        $rolEvaluador = Role::firstOrCreate([
            'name' => 'Evaluador SECOEM',
            'guard_name' => 'web',
        ]);

        $rolOperacion = Role::firstOrCreate([
            'name' => 'Operación Técnica',
            'guard_name' => 'web',
        ]);

        /*
         * =====================================================
         * ENLACE DEL COMITÉ
         * =====================================================
         *
         * Puede capturar y enviar información de su Comité,
         * pero no puede validar ni evaluar.
         */

        $rolEnlace->syncPermissions([
            'tablero.ver',

            'planes-anuales.ver',
            'planes-anuales.crear',
            'planes-anuales.editar',
            'planes-anuales.cargar',
            'planes-anuales.enviar',

            'sesiones.ver',
            'sesiones.crear',
            'sesiones.editar',
            'sesiones.enviar',

            'documentos.ver',
            'documentos.cargar',
            'documentos.descargar',

            'evaluaciones.ver',

            'cedulas.ver',
            'cedulas.descargar',
        ]);

        /*
         * =====================================================
         * EVALUADOR SECOEM
         * =====================================================
         *
         * Revisa la información enviada por los Comités,
         * realiza observaciones, valida y asigna la evaluación.
         */

        $rolEvaluador->syncPermissions([
            'tablero.ver',

            'entes.ver',

            'comites.ver',

            'planes-anuales.ver',
            'planes-anuales.revisar',
            'planes-anuales.observar',
            'planes-anuales.validar',

            'sesiones.ver',
            'sesiones.revisar',
            'sesiones.observar',
            'sesiones.validar',

            'documentos.ver',
            'documentos.descargar',

            'evaluaciones.ver',
            'evaluaciones.crear',
            'evaluaciones.calificar',
            'evaluaciones.finalizar',

            'cedulas.ver',
            'cedulas.emitir',
            'cedulas.descargar',

            'reportes.ver',
            'reportes.generar',
            'reportes.exportar',
        ]);

        /*
         * =====================================================
         * OPERACIÓN TÉCNICA
         * =====================================================
         *
         * Administra técnicamente el sistema.
         *
         * IMPORTANTE:
         * deliberadamente no puede:
         *
         * - validar sesiones
         * - calificar evaluaciones
         * - finalizar evaluaciones
         *
         * para mantener separación entre operación técnica
         * y funciones sustantivas de SECOEM.
         */

        $rolOperacion->syncPermissions([
            'tablero.ver',

            'entes.ver',
            'entes.crear',
            'entes.editar',
            'entes.eliminar',

            'comites.ver',
            'comites.crear',
            'comites.editar',
            'comites.eliminar',

            'planes-anuales.ver',

            'sesiones.ver',

            'documentos.ver',
            'documentos.descargar',

            'evaluaciones.ver',

            'cedulas.ver',
            'cedulas.descargar',

            'reportes.ver',
            'reportes.generar',
            'reportes.exportar',

            'catalogos.ver',
            'catalogos.crear',
            'catalogos.editar',
            'catalogos.eliminar',

            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'usuarios.asignar-roles',

            'operacion.supervisar',
            'operacion.ver-auditoria',
        ]);

        /*
         * Volver a limpiar caché después de crear
         * y asignar todos los permisos.
         */
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}