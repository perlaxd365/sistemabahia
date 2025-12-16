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
        Schema::create('atencion_servicios', function (Blueprint $table) {
            $table->bigIncrements('id_atencion_servicio');
            $table->unsignedBigInteger('id_atencion')->comment('id de la atencion');
            $table->unsignedBigInteger('id_servicio')->comment('id del servicio');
            $table->unsignedBigInteger('id_profesional')->comment('id del profesional');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 6, 2);
            $table->decimal('subtotal', 6, 2);
            $table->boolean('estado');
            $table->timestamps();

            //foreign keys
            $table->foreign('id_atencion')->references('id_atencion')->on('atencions');
            $table->foreign('id_servicio')->references('id_servicio')->on('servicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencion_servicios');
    }
};
