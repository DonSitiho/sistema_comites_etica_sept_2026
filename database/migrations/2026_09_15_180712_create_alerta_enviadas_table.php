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
        Schema::create('alertas_enviadas', function (
            Blueprint $table
        ) {
            $table->id();

            $table->foreignId('sesion_comite_id')
                ->constrained('sesiones_comite')
                ->cascadeOnDelete();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('tipo', 40);

            $table->timestamp('fecha_envio');

            $table->timestamps();

            $table->unique([
                'sesion_comite_id',
                'usuario_id',
                'tipo',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerta_enviadas');
    }
};
