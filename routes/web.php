<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Etica\DocumentoController;
use App\Http\Controllers\Etica\SesionComiteController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    

});


// Rutas para el módulo de ética
    Route::middleware([
            'auth',
        ])
        ->prefix('etica')
        ->name('etica.')
        ->group(function () {

            /*
            * =====================================================
            * SESIONES
            * =====================================================
            */

            Route::put(
                '/sesiones/{sesion}/actualizar',
                [
                    SesionComiteController::class,
                    'actualizar',
                ]
            )->name(
                'sesiones.actualizar'
            );

            Route::post(
                '/sesiones/{sesion}/enviar',
                [
                    SesionComiteController::class,
                    'enviar',
                ]
            )->name(
                'sesiones.enviar'
            );

            Route::post(
                '/sesiones/{sesion}/iniciar-revision',
                [
                    SesionComiteController::class,
                    'iniciarRevision',
                ]
            )->name(
                'sesiones.iniciar-revision'
            );

            Route::post(
                '/sesiones/{sesion}/observar',
                [
                    SesionComiteController::class,
                    'observar',
                ]
            )->name(
                'sesiones.observar'
            );

            Route::post(
                '/sesiones/{sesion}/validar',
                [
                    SesionComiteController::class,
                    'validar',
                ]
            )->name(
                'sesiones.validar'
            );

            /*
            * =====================================================
            * DOCUMENTOS
            * =====================================================
            */

            Route::post(
                '/sesiones/{sesion}/documentos',
                [
                    DocumentoController::class,
                    'guardar',
                ]
            )->name(
                'sesiones.documentos.guardar'
            );

            Route::get(
                '/documentos/{documento}/descargar',
                [
                    DocumentoController::class,
                    'descargar',
                ]
            )->name(
                'documentos.descargar'
            );
        });


Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
