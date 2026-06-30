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
        Schema::create('catalogo_procedimientos', function (Blueprint $table) {

            $table->id();

            $table->string('codigo', 20)->unique();
            $table->text('denominacion');

            $table->string('grupo_codigo', 20)->nullable();
            $table->string('grupo_nombre')->nullable();

            $table->string('seccion_codigo', 20)->nullable();
            $table->string('seccion_nombre')->nullable();

            $table->string('subseccion_codigo', 20)->nullable();
            $table->string('subseccion_nombre')->nullable();

            $table->enum('situacion', [
                'ACTIVO',
                'INACTIVO'
            ])->default('ACTIVO');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_procedimientos');
    }
};
