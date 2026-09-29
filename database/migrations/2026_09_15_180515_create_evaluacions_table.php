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
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('comite_id')
                ->constrained('comites')
                ->restrictOnDelete();

            $table->unsignedSmallInteger('ejercicio');

            /*
            * Componente automático.
            * Rango 0 - 25.
            */
            $table->decimal(
                'puntaje_cumplimiento',
                5,
                2
            )->default(0);

            /*
            * Componente manual SECOEM.
            * Rango 0 - 75.
            */
            $table->decimal(
                'puntaje_desempeno',
                5,
                2
            )->default(0);

            /*
            * Resultado 0 - 100.
            */
            $table->decimal(
                'calificacion_final',
                5,
                2
            )->default(0);

            $table->string('semaforo', 20)
                ->nullable();

            $table->string('estado', 40)
                ->default('borrador')
                ->index();

            $table->text('observaciones_generales')
                ->nullable();

            $table->foreignId('evaluador_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('fecha_evaluacion')
                ->nullable();

            $table->timestamp('fecha_finalizacion')
                ->nullable();

            $table->timestamp('fecha_emision')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'comite_id',
                'ejercicio',
            ]);

            $table->index([
                'ejercicio',
                'estado',
            ]);

            $table->index([
                'ejercicio',
                'semaforo',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacions');
    }
};
