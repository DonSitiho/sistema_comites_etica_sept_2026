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
        Schema::create('comites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ente_id')
                ->constrained('entes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('nombre', 255);

            $table->date('fecha_instalacion')
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            $table->timestamps();

            $table->unique('ente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comites');
    }
};
