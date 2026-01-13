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
        Schema::create('comprobante_detalles', function (Blueprint $table) {
            $table->bigIncrements('id_detalle_comprobante');
            $table->unsignedBigInteger('id_comprobante');

            // Producto / Servicio
            $table->string('descripcion');
            $table->string('codigo')->nullable(); // SUNAT o interno
            $table->string('unidad', 10)->default('NIU');

            // Cantidades y precios
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);

            // SUNAT
            $table->string('tipo_afectacion_igv', 2)->default('10'); // gravado
            $table->decimal('igv', 10, 2)->default(0);

            $table->timestamps();

            $table->foreign('id_comprobante')
                ->references('id_comprobante')
                ->on('comprobantes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes_detalles');
    }
};
