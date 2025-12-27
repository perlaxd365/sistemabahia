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
        Schema::create('compras', function (Blueprint $table) {
            $table->bigIncrements('id_compra');
            $table->unsignedBigInteger('id_proveedor')->comment('id del proveedorr');
            $table->date('fecha_compra');
            $table->string('tipo_documento')->nullable();
            $table->string('nro_documento')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('estado', ['ACTIVA', 'ANULADA'])->default('ACTIVA');
            $table->text('motivo_anulacion')->nullable();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->timestamps();


            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
