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
        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id('id_caja_movimiento');
            $table->unsignedBigInteger('id_referencia')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_caja_turno');

            $table->enum('tipo', ['INGRESO', 'EGRESO']);
            $table->string('descripcion');
            $table->decimal('monto', 10, 2);
            $table->string('responsable')->nullable();

            $table->timestamps();

            
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_movimientos');
    }
};
