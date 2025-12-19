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
        Schema::create('signos', function (Blueprint $table) {

            $table->bigIncrements('id_signo');
            $table->unsignedBigInteger('id_atencion')->nullable()->comment('id de la atencion');
            $table->unsignedBigInteger('id_paciente')->nullable()->comment('id del paciente');

            // Brazo derecho
            $table->string('sistolica_derecha')->nullable();
            $table->string('diastolica_derecha')->nullable();

            // Brazo izquierdo
            $table->string('sistolica_izquierda')->nullable();
            $table->string('diastolica_izquierda')->nullable();

            $table->string('frecuencia_cardiaca')->nullable();
            $table->string('fecha_signo');
            $table->boolean('estado_signo');
            $table->timestamps();


            //foreign keys
            $table->foreign('id_atencion')->references('id_atencion')->on('atencions');
            $table->foreign('id_paciente')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signos');
    }
};
