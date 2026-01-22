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
        Schema::create('caja_chicas', function (Blueprint $table) {
            $table->bigIncrements('id_caja_chica');

            // Relación con turno de caja
            $table->unsignedBigInteger('id_caja_turno');

            // Detalle del egreso
            $table->string('descripcion');
            $table->decimal('monto', 10, 2);

            // Quién realiza o autoriza el gasto
            $table->string('responsable')->nullable();

            // Auditoría
            $table->dateTime('fecha_movimiento');
            $table->enum('estado', ['REGISTRADO', 'ANULADO'])->default('REGISTRADO');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            // Foreign keys
            $table->foreign('id_caja_turno')
                ->references('id_caja_turno')
                ->on('caja_turnos');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_chicas');
    }
};
