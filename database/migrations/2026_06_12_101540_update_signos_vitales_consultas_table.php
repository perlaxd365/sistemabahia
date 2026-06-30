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
        Schema::table('consultas', function (Blueprint $table) {

            $table->string('frecuencia_respiratoria_consulta')
                ->nullable()
                ->after('frecuencia_consulta');

            $table->string('fio2_consulta')
                ->nullable()
                ->after('saturacion_consulta');
        });
    }

    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {

            $table->dropColumn([
                'frecuencia_respiratoria_consulta',
                'fio2_consulta'
            ]);
        });
    }
};
