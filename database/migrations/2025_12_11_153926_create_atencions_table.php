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
        Schema::create('atencions', function (Blueprint $table) {
            $table->bigIncrements('id_atencion');
            $table->unsignedBigInteger('id_paciente')->comment('id del paciente');
            $table->unsignedBigInteger('id_historia')->comment('id de la historia');
            $table->unsignedBigInteger('id_responsable')->comment('id del responsable');
            $table->unsignedBigInteger('id_medico')->nullable()->comment('id del medico');
            $table->longText('tipo_atencion');
            $table->string('fecha_inicio_atencion');
            $table->string('fecha_fin_atencion')->nullable();
            $table->enum('estado', ['PROCESO', 'FINALIZADO','ANULADO'])->default('PROCESO');
            $table->timestamps();

            //foreign keys
            $table->foreign('id_historia')->references('id_historia')->on('historias');
            $table->foreign('id_paciente')->references('id')->on('users');
            $table->foreign('id_responsable')->references('id')->on('users');
            $table->foreign('id_medico')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencions');
    }
};
