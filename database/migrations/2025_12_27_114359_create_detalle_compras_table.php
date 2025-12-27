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
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->bigIncrements('id_detallecompra');
            // FK COMPRA
            $table->unsignedBigInteger('id_compra');

            // FK MEDICAMENTO
            $table->unsignedBigInteger('id_medicamento');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            // FK COMPRA (PK personalizada)
            $table->foreign('id_compra')
                ->references('id_compra')
                ->on('compras')
                ->cascadeOnDelete();

            $table->foreign('id_medicamento')->references('id_medicamento')->on('medicamentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_compras');
    }
};
