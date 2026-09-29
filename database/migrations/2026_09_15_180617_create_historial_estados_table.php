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
        Schema::create('historiales_estado', function (
            Blueprint $table
        ) {
            $table->id();

            /*
            * Ejemplos:
            *
            * App\Models\SesionComite
            * App\Models\PlanAnual
            * App\Models\Evaluacion
            */
            $table->string('entidad_tipo');

            $table->unsignedBigInteger('entidad_id');

            $table->string('estado_anterior', 40)
                ->nullable();

            $table->string('estado_nuevo', 40);

            $table->text('motivo')
                ->nullable();

            $table->foreignId('cambiado_por')
                ->constrained('users')
                ->restrictOnDelete();

            $table->ipAddress('direccion_ip')
                ->nullable();

            $table->text('agente_usuario')
                ->nullable();

            $table->timestamp('fecha_cambio');

            $table->timestamps();

            $table->index([
                'entidad_tipo',
                'entidad_id',
            ]);

            $table->index('fecha_cambio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_estados');
    }
};
