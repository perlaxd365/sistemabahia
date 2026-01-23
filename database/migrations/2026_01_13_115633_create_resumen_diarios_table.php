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
        Schema::create('resumen_diarios', function (Blueprint $table) {
            $table->id('id_resumen');
            $table->date('fecha');
            $table->string('ticket')->nullable();
            $table->enum('estado', ['PENDIENTE', 'ENVIADO', 'ACEPTADO', 'RECHAZADO'])->default('PENDIENTE');
            $table->text('respuesta_sunat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumen_diarios');
    }
};
