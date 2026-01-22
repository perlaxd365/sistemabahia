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
            $table->unsignedBigInteger('id_atencion');
            $table->unsignedBigInteger('id_caja_turno')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->enum('tipo_pago', [
                'EFECTIVO',
                'TARJETA',
                'YAPE',
                'PLIN',
                'TRANSFERENCIA'
            ]);

            $table->decimal('monto', 10, 2);
            $table->dateTime('fecha_pago');
            $table->enum('estado', ['REGISTRADO', 'ANULADO'])
                ->default('REGISTRADO');

            /////////
            $table->timestamps();

            $table->foreign('id_comprobante')->references('id_comprobante')->on('comprobantes')->onDelete('cascade');

            $table->foreign('id_caja_turno')->references('id_caja_turno')->on('caja_turnos');
            $table->foreign('id_atencion')->references('id_atencion')->on('atencions');
            $table->foreign('user_id')->references('id')->on('users');
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
