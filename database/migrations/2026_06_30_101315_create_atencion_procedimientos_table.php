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
        Schema::create('atencion_procedimientos', function (Blueprint $table) {
            $table->id();

            // Relación con la atención
            $table->unsignedBigInteger('id_atencion');

            // Código oficial del procedimiento (SUSALUD)
            $table->string('codigo_procedimiento', 20);

            // Descripción del procedimiento
            $table->string('descripcion');

            // Código UPS
            $table->string('codigo_ups', 10);

            // Nombre de la UPS (opcional)
            $table->string('nombre_ups')->nullable();

            // Cantidad realizada
            $table->unsignedInteger('cantidad')->default(1);

            $table->timestamps();

            $table->foreign('id_atencion')
                ->references('id_atencion')
                ->on('atencions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencion_procedimientos');
    }
};
