<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atencion_medicamentos', function (Blueprint $table) {

            $table->enum('origen', [
                'FARMACIA',
                'SALA',
                'HOSPITALIZACION',
                'ALMACEN'
            ])
                ->default('FARMACIA')
                ->after('tipo');
        });
    }

    public function down(): void
    {
        Schema::table('atencion_medicamentos', function (Blueprint $table) {
            $table->dropColumn('origen');
        });
    }
};
