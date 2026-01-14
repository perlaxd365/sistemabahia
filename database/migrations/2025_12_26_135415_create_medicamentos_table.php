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
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->bigIncrements('id_medicamento');

            $table->string('nombre');
            $table->string('presentacion')->nullable(); // Tableta, jarabe, ampolla
            $table->string('concentracion')->nullable(); // 500mg, 5mg/ml

            $table->integer('stock')->nullable()->default(0);
            $table->decimal('precio_venta', 10, 2)->nullable()->default(0);

            $table->string('marca')->nullable();
            $table->string('fecha_vencimiento')->nullable();

            $table->boolean('estado')->default(true);




            //sunat 
            $table->string('codigo_sunat')->nullable();
            $table->string('unidad_sunat', 10)->default('NIU');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
