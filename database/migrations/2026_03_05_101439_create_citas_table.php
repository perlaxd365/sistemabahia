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
        Schema::create('citas', function (Blueprint $table) {

            $table->id('id_cita');

            $table->unsignedBigInteger('id_paciente');

            $table->string('medico')->nullable(); // nombre del médico
            $table->string('especialidad')->nullable();

            $table->date('fecha_cita');
            $table->time('hora_cita');

            $table->string('motivo')->nullable();

            $table->enum('estado', [
                'PROGRAMADA',
                'CONFIRMADA',
                'ATENDIDA',
                'CANCELADA',
                'NO_ASISTIO'
            ])->default('PROGRAMADA');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
