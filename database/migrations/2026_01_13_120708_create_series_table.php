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
        Schema::create('sunat_series', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_comprobante'); // FACTURA | BOLETA
            $table->string('serie');            // FFF1 | BBB1
            $table->integer('ultimo_numero');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
