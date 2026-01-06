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
        Schema::create('imagen_informes', function (Blueprint $table) {
            $table->bigIncrements('id_informe');
            $table->unsignedBigInteger('id_detalle_imagen');
            $table->text('informe')->nullable();
            $table->string('archivo')->nullable(); // pdf / jpg / png
            $table->date('fecha_informe')->nullable();
            $table->timestamps();

            $table->foreign('id_detalle_imagen')
                ->references('id_detalle_imagen')
                ->on('orden_imagen_detalles')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagen_informes');
    }
};
