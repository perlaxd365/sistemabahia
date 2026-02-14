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
        Schema::table('atencions', function (Blueprint $table) {

            // Agregar columnas necesarias
            $table->date('fecha_atencion')->nullable()->after('tipo_atencion');
            $table->time('hora_atencion')->nullable()->after('fecha_atencion');

            $table->string('codigo_renipress')->nullable()->after('hora_atencion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
