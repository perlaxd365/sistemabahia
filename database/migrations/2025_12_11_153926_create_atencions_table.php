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
            $table->unsignedBigInteger('id_historia')->comment('id de la historia');
            $table->longText('tipo_atencion');
            $table->string('fecha_inicio_atencion');
            $table->string('fecha_fin_atencion');
            $table->boolean('estado_atencion');
            $table->timestamps();

            //foreign keys
            $table->foreign('id_historia')->references('id_historia')->on('historias');
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
