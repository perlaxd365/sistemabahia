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
        Schema::create('catalogo_ups', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 20)->unique()->comment('CO_CODUPS');
            $table->string('nombre')->comment('DE_NOMBREUPS');

            $table->boolean('tabla_d1')->default(false)->comment('TABLA_D1_SETIIPRESS');
            $table->boolean('tabla_g')->default(false)->comment('TABLA_G_SETIIPRESS');
            $table->boolean('tabla_i')->default(false)->comment('TABLA_I_SETIIPRESS');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_ups');
    }
};
