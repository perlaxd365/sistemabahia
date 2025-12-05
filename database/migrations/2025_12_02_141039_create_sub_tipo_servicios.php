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
        Schema::create('sub_tipo_servicios', function (Blueprint $table) {
            $table->bigIncrements('id_subtipo_servicio');
            $table->unsignedBigInteger('id_tipo_servicio')->nullable()->comment('id del tipo de servicio');
            $table->string('nombre_subtipo_servicio');
            $table->boolean('estado_subtipo_servicio');
            $table->timestamps();

            //foreign keys
            $table->foreign('id_tipo_servicio')->references('id_tipo_servicio')->on('tipo_servicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_tipo_servicios');
    }
};
