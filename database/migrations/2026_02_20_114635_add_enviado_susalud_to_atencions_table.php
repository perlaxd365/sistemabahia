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
            $table->boolean('enviado_susalud')
                ->default(false)->nullable()
                ->after('fecha_fin_atencion'); // puedes cambiar la columna de referencia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atencions', function (Blueprint $table) {
            $table->dropColumn('enviado_susalud');
        });
    }
};
