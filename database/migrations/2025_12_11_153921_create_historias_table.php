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
        Schema::create('historias', function (Blueprint $table) {
            $table->bigIncrements('id_historia');
            $table->unsignedBigInteger('id_paciente')->comment('id del paciente');
            $table->string('nro_historia');
            $table->string('fecha_historia');
            $table->boolean('estado_historia');
            $table->timestamps();

            //foreign keys
            $table->foreign('id_paciente')->references('id')->on('users');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historias');
    }
};
