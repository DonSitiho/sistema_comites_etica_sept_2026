<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sesiones_comite', function (Blueprint $table) {
            $table->id();

            $table->foreignId('comite_id')
                ->constrained('comites')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('plan_anual_id')
                ->constrained('planes_anuales')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedSmallInteger('ejercicio');

            $table->unsignedTinyInteger('trimestre');

            /*
            * Fecha efectiva en que sesionó el Comité.
            */
            $table->date('fecha_sesion')
                ->nullable();

            /*
            * Fecha límite establecida por SECOEM.
            */
            $table->date('fecha_limite');

            /*
            * Una vez que el enlace envía por primera vez,
            * fecha_sesion queda permanentemente congelada.
            */
            $table->timestamp('fecha_bloqueada_en')
                ->nullable();

            $table->string('estado', 40)
                ->default('pendiente')
                ->index();

            $table->text('comentarios_enlace')
                ->nullable();

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

            /*
            * Control de concurrencia.
            */
            $table->unsignedInteger('version')
                ->default(1);

            $table->timestamps();

            $table->unique([
                'comite_id',
                'ejercicio',
                'trimestre',
            ]);

            $table->index([
                'ejercicio',
                'trimestre',
                'estado',
            ]);

            $table->index([
                'fecha_limite',
                'estado',
            ]);
        });
    }

    //     DB::statement("
    //     ALTER TABLE sesiones_comite
    //     ADD CONSTRAINT chk_sesiones_comite_trimestre
    //     CHECK (trimestre BETWEEN 1 AND 4)
    // ");

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_comites');
    }
};
