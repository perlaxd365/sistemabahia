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
            $table->string('tipo_atencion', 2)
                ->after('relato_consulta')
                ->comment('01=Consulta Externa, 02=Emergencia, 03=Hospitalizacion, 04=Apoyo al Diagnóstico , 05=Ambulatorio ')->nullable();
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
