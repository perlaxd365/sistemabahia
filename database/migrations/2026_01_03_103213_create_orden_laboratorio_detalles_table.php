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
        Schema::create('orden_laboratorio_detalles', function (Blueprint $table) {
            $table->bigIncrements('id_detalle_laboratorio');
            $table->unsignedBigInteger('id_orden');
            $table->unsignedBigInteger('id_examen')->nullable();
             $table->string('examen_manual')->nullable();
            
            $table->timestamps();

            $table->foreign('id_orden')
                ->references('id_orden')
                ->on('orden_laboratorios')
                ->cascadeOnDelete();

            $table->foreign('id_examen')
                ->references('id_examen')
                ->on('laboratorio_examens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_laboratorio_detalles');
    }
};
