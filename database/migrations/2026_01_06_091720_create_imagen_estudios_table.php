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
        Schema::create('imagen_estudios', function (Blueprint $table) {
            $table->bigIncrements('id_estudio');
            $table->unsignedBigInteger('id_area_imagen');
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_area_imagen')
                ->references('id_area_imagen')
                ->on('imagen_areas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagen_estudios');
    }
};
