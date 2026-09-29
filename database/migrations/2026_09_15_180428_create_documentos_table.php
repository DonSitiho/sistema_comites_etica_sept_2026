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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tipo_documento_id')
                ->constrained('tipos_documento')
                ->restrictOnDelete();

            $table->foreignId('plan_anual_id')
                ->nullable()
                ->constrained('planes_anuales')
                ->restrictOnDelete();

            $table->foreignId('sesion_comite_id')
                ->nullable()
                ->constrained('sesiones_comite')
                ->restrictOnDelete();

            $table->string('nombre_original', 255);

            $table->string('nombre_almacenado', 255);

            $table->string('ruta', 500);

            $table->string('tipo_mime', 100);

            $table->unsignedBigInteger('tamano');

            /*
            * Integridad documental.
            */
            $table->char('hash_sha256', 64);

            $table->unsignedSmallInteger('version')
                ->default(1);

            $table->boolean('es_actual')
                ->default(true);

            /*
            * Documento sustituido.
            */
            $table->foreignId('documento_anterior_id')
                ->nullable()
                ->constrained('documentos')
                ->nullOnDelete();

            $table->foreignId('cargado_por')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('fecha_carga');

            $table->timestamps();

            $table->index([
                'sesion_comite_id',
                'tipo_documento_id',
                'es_actual',
            ]);

            $table->index([
                'plan_anual_id',
                'tipo_documento_id',
                'es_actual',
            ]);

            $table->index('hash_sha256');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
