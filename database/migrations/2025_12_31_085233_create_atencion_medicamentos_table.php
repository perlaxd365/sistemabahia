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
        Schema::create('atencion_medicamentos', function (Blueprint $table) {
            $table->bigIncrements('id_atencion_medicamento');

            $table->unsignedBigInteger('id_atencion');
            $table->unsignedBigInteger('id_medicamento');

            $table->integer('cantidad');
            $table->decimal('precio', 10, 2)->nullable(); // si se vende
            $table->decimal('subtotal', 12, 2)->nullable();

            $table->enum('tipo', ['RECETA', 'VENTA'])->default('RECETA');

            $table->timestamps();

            // Relaciones
            $table->foreign('id_atencion')
                ->references('id_atencion')
                ->on('atencions')
                ->cascadeOnDelete();

            $table->foreign('id_medicamento')
                ->references('id_medicamento')
                ->on('medicamentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencion_medicamentos');
    }
};
