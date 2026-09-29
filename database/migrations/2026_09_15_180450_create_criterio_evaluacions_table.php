<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('criterios_evaluacion', function (
            Blueprint $table
        ) {
            $table->id();

            $table->unsignedSmallInteger('ejercicio');

            $table->string('clave', 50);

            $table->string('nombre', 255);

            $table->text('descripcion')
                ->nullable();

            $table->decimal(
                'puntaje_maximo',
                5,
                2
            );

            $table->unsignedSmallInteger('orden')
                ->default(1);

            $table->boolean('activo')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'ejercicio',
                'clave',
            ]);

            $table->index([
                'ejercicio',
                'activo',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterio_evaluacions');
    }
};
