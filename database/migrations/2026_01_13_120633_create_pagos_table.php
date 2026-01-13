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
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->unsignedBigInteger('id_comprobante');

            $table->enum('tipo_pago', [
                'EFECTIVO',
                'TARJETA',
                'YAPE',
                'PLIN',
                'TRANSFERENCIA'
            ]);

            $table->decimal('monto', 10, 2);
            $table->dateTime('fecha_pago');

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
        Schema::dropIfExists('pagos');
    }
};
