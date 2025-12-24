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
        Schema::create('consultas', function (Blueprint $table) {
            $table->bigIncrements('id_consulta');
            $table->unsignedBigInteger('id_atencion')->comment('id de la atencion');
            $table->unsignedBigInteger('id_paciente')->comment('id del paciente');
            $table->unsignedBigInteger('id_responsable')->comment('id del responsable');

            // enfermedad actual
            $table->string('molestia_consulta')->nullable();
            $table->string('tiempo_consulta')->nullable();
            $table->string('inicio_consulta')->nullable();
            $table->string('curso_consulta')->nullable();
            $table->text('enfermedad_consulta')->nullable();
            $table->text('atecedente_familiar_consulta')->nullable();
            $table->text('atecedente_patologico_consulta')->nullable();

            // examen fisico
            $table->string('peso_consulta')->nullable();
            $table->string('talla_consulta')->nullable();
            $table->string('imc_consulta')->nullable();

            $table->string('temperatura_consulta')->nullable();
            $table->string('presion_consulta')->nullable();
            $table->string('frecuencia_consulta')->nullable();
            $table->string('saturacion_consulta')->nullable();
            $table->text('examen_consulta')->nullable();

            // impresion diagnostica
            $table->text('impresion_consulta')->nullable();

            // plan
            $table->text('examen_auxiliar_consulta')->nullable();
            $table->text('tratamiento_consulta')->nullable();

            $table->string('fecha_consulta');
            $table->boolean('estado_consulta');
            $table->timestamps();


            //foreign keys
            $table->foreign('id_atencion')->references('id_atencion')->on('atencions');
            $table->foreign('id_paciente')->references('id')->on('users');
            $table->foreign('id_responsable')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
