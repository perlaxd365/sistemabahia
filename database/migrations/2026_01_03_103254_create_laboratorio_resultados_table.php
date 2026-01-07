<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laboratorio_resultados', function (Blueprint $table) {
            $table->bigIncrements('id_resultado');
            $table->unsignedBigInteger('id_detalle_laboratorio');
            $table->longText('resultado')->nullable();
            $table->string('observacion')->nullable();
            $table->date('fecha_resultado')->nullable();
            $table->timestamps();

            $table->foreign('id_detalle_laboratorio')
                ->references('id_detalle_laboratorio')
                ->on('orden_laboratorio_detalles')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio_resultados');
    }
};
