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
        Schema::create('detalles_evaluacion', function (
            Blueprint $table
        ) {
            $table->id();

            $table->foreignId('evaluacion_id')
                ->constrained('evaluaciones')
                ->cascadeOnDelete();

            $table->foreignId('criterio_evaluacion_id')
                ->constrained('criterios_evaluacion')
                ->restrictOnDelete();

            $table->decimal(
                'puntaje_obtenido',
                5,
                2
            )->default(0);

            $table->text('observaciones')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'evaluacion_id',
                'criterio_evaluacion_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_evaluacions');
    }
};
