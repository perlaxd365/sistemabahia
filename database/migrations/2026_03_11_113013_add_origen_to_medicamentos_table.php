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
        Schema::table('medicamentos', function (Blueprint $table) {

            $table->enum('origen', [
                'FARMACIA',
                'SALA',
                'HOSPITALIZACION',
                'ALMACEN'
            ])
                ->default('FARMACIA')
                ->after('precio_venta');
        });
    }

    public function down(): void
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->dropColumn('origen');
        });
    }
};
