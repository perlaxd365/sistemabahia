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
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('id_servicio');
            $table->unsignedBigInteger('id_subtipo_servicio')->nullable()->comment('id del subtipo de servicio');
            $table->string('nombre_servicio');
            $table->decimal('precio_servicio', 6, 2);
            $table->boolean('estado_servicio');

            //sunat 
            $table->string('codigo_sunat')->nullable();
            $table->string('unidad_sunat', 10)->default('NIU');
            $table->decimal('precio', 10, 2);


            $table->timestamps();


            //foreign keys
            $table->foreign('id_subtipo_servicio')->references('id_subtipo_servicio')->on('sub_tipo_servicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
