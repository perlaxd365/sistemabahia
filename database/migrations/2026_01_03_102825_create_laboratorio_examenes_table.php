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
        Schema::create('laboratorio_examens', function (Blueprint $table) {
            $table->bigIncrements('id_examen');
            $table->unsignedBigInteger('id_area');
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->string('unidad')->nullable();
            $table->string('valor_referencia')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_area')
                ->references('id_area')
                ->on('laboratorio_areas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio_examens');
    }
};
