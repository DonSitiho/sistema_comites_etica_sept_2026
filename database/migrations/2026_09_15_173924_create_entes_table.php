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
        Schema::create('entes', function (Blueprint $table) {
            $table->id();

            $table->string('clave', 30)->unique();
            $table->string('nombre', 255);
            $table->string('siglas', 50)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entes');
    }
};
