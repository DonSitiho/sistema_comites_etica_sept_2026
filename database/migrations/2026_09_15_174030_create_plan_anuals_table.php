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
        Schema::create('planes_anuales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('comite_id')
                ->constrained('comites')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedSmallInteger('ejercicio');

            $table->string('estado', 40)
                ->default('pendiente')
                ->index();

            $table->timestamp('fecha_envio')
                ->nullable();

            $table->timestamp('fecha_inicio_revision')
                ->nullable();

            $table->timestamp('fecha_observacion')
                ->nullable();

            $table->timestamp('fecha_validacion')
                ->nullable();

            $table->foreignId('creado_por')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('revisado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('observaciones')
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_anuals');
    }
};
