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
        Schema::create('farmacia_salidas_internas', function (Blueprint $table) {

            $table->id('id_salida_interna');

            $table->dateTime('fecha');

            $table->unsignedBigInteger('id_medicamento');
            $table->unsignedBigInteger('id_atencion')->nullable();
            $table->decimal('cantidad', 10, 2);

            $table->enum('motivo', [
                'uso_atencion',
                'consumo_area',
                'emergencia',
                'descarte',
                'ajuste'
            ]);


            $table->text('observacion')->nullable();
            // agregar área selectiva
            $table->enum('area', [
                'TOPICO',
                'EMERGENCIA',
                'QUIROFANO',
                'TRIAJE',
                'HOSPITALIZACION',
                'CONSULTORIO'
            ]);

            $table->unsignedBigInteger('id_usuario');

            $table->timestamps();

            /*
            |---------------------------------------------------
            | FOREIGN KEYS
            |---------------------------------------------------
            */
            $table->foreign('id_medicamento')
                ->references('id_medicamento')
                ->on('medicamentos');

            $table->foreign('id_atencion')
                ->references('id_atencion')
                ->on('atencions');

            $table->foreign('id_usuario')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmacia_salidas_internas');
    }
};
