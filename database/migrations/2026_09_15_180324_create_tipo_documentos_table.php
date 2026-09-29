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
        Schema::create('tipos_documento', function (Blueprint $table) {
            $table->id();

            $table->string('clave', 50)
                ->unique();

            $table->string('nombre', 150);

            $table->text('descripcion')
                ->nullable();

            $table->boolean('aplica_plan_anual')
                ->default(false);

            $table->boolean('aplica_sesion')
                ->default(false);

            $table->boolean('obligatorio')
                ->default(true);

            $table->unsignedSmallInteger('orden')
                ->default(1);

            $table->boolean('activo')
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documentos');
    }
};
