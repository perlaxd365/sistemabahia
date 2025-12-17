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
            $table->unsignedBigInteger('id_profesional')->nullable()->comment('id del profesional');
            $table->unsignedBigInteger('id_responsable')->comment('id del que inicio sesion');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 6, 2);
            $table->decimal('subtotal', 6, 2);
            $table->boolean('estado');
            $table->timestamps();

            //foreign keys
            $table->foreign('id_atencion')->references('id_atencion')->on('atencions');
            $table->foreign('id_servicio')->references('id_servicio')->on('servicios');
            $table->foreign('id_profesional')->references('id')->on('users');
            $table->foreign('id_responsable')->references('id')->on('users');
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
