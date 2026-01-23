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
            $table->bigIncrements('id_caja_movimiento');

            // Turno de caja (OBLIGATORIO)
            $table->unsignedBigInteger('id_caja_turno');

            // Usuario que registra
            $table->unsignedBigInteger('id_usuario');

            // Referencia opcional (pago, caja chica, ajuste, etc.)
            $table->unsignedBigInteger('id_referencia')->nullable();
            $table->string('tabla_referencia')->nullable();
            // Ej: pagos | caja_chicas | ajustes

            // Tipo de movimiento
            $table->enum('tipo', ['INGRESO', 'EGRESO']);

            $table->string('descripcion');
            $table->decimal('monto', 10, 2);
            $table->string('responsable')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('id_usuario')
                ->references('id')
                ->on('users');

            $table->foreign('id_caja_turno')
                ->references('id_caja_turno')
                ->on('caja_turnos');
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
