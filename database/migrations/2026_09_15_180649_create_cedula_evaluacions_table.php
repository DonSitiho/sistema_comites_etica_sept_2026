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
        Schema::create('cedulas_evaluacion', function (
            Blueprint $table
        ) {
            $table->id();

            $table->foreignId('evaluacion_id')
                ->constrained('evaluaciones')
                ->restrictOnDelete();

            $table->string('folio', 50)
                ->unique();

            $table->string('ruta', 500);

            $table->char('hash_sha256', 64);

            $table->timestamp('fecha_emision');

            $table->foreignId('emitida_por')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique('evaluacion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cedula_evaluacions');
    }
};
