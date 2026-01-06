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
        Schema::create('orden_imagen_detalles', function (Blueprint $table) {
            $table->bigIncrements('id_detalle_imagen');
            $table->unsignedBigInteger('id_orden_imagen');
            $table->unsignedBigInteger('id_estudio')->nullable();
            $table->string('descripcion_manual')->nullable();
            $table->timestamps();

            $table->foreign('id_orden_imagen')
                ->references('id_orden_imagen')
                ->on('orden_imagenes')
                ->cascadeOnDelete();

            $table->foreign('id_estudio')
                ->references('id_estudio')
                ->on('imagen_estudios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_imagen_detalles');
    }
};
