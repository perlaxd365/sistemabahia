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
        Schema::create('kardex_medicamentos', function (Blueprint $table) {
            $table->bigIncrements('id_kardex');

            $table->unsignedBigInteger('id_medicamento');

            // Relación opcional
            $table->unsignedBigInteger('id_compra')->nullable();
            $table->unsignedBigInteger('id_atencion')->nullable();

            $table->enum('tipo_movimiento', ['ENTRADA', 'SALIDA']);

            $table->integer('cantidad');

            $table->integer('stock_anterior');
            $table->integer('stock_actual');

            $table->string('descripcion')->nullable(); // ej: Compra proveedor / Dispensación paciente

            $table->unsignedBigInteger('user_id')->nullable(); // bonus auditoría
            $table->timestamps();

            // Relaciones
            $table->foreign('id_medicamento')->references('id_medicamento')->on('medicamentos');

            $table->foreign('id_compra')->references('id_compra')->on('compras')->nullOnDelete();

            $table->foreign('id_atencion')->references('id_atencion')->on('atencions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kardex_medicamentos');
    }
};
