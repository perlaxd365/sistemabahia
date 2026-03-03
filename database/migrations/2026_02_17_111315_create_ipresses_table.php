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
        Schema::create('ipresses', function (Blueprint $table) {
            $table->id();
            $table->string('renipress', 8);
            $table->string('ruc', 11);
            $table->string('razon_social');
            $table->string('nombre_comercial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipresses');
    }
};
