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
            $table->enum('modo_atencion', ['MEDICA', 'PARTICULAR'])
                ->default('MEDICA')
                ->after('tipo_atencion');
        });
    }

    public function down(): void
    {
        Schema::table('atencions', function (Blueprint $table) {
            $table->dropColumn('modo_atencion');
        });
    }
};
