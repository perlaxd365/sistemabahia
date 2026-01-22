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
        Schema::create('caja_turnos', function (Blueprint $table) {
            $table->id('id_caja_turno');

            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_cierre')->nullable();

            $table->decimal('monto_apertura', 10, 2);
            $table->decimal('monto_cierre', 10, 2)->nullable();

            $table->enum('estado', ['ABIERTO', 'CERRADO'])->default('ABIERTO');

            $table->foreignId('id_usuario_apertura')->constrained('users');
            $table->foreignId('id_usuario_cierre')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_turnos');
    }
};
