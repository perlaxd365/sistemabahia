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
        Schema::table('atencion_procedimientos', function (Blueprint $table) {

            $table->unsignedBigInteger('id_catalogo_ups')
                ->nullable()
                ->after('id_catalogo_procedimiento');

            $table->foreign('id_catalogo_ups')
                ->references('id')
                ->on('catalogo_ups')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atencion_procedimientos', function (Blueprint $table) {

            $table->dropForeign(['id_catalogo_ups']);

            $table->dropColumn('id_catalogo_ups');
        });
    }
};
