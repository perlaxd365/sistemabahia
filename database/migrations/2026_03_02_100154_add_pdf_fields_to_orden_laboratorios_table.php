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
        Schema::table('orden_laboratorios', function (Blueprint $table) {
            //
            $table->string('ruta_pdf_resultado')->nullable()->after('estado');
            $table->timestamp('fecha_subida_pdf')->nullable();
            $table->unsignedBigInteger('id_usuario_subida_pdf')->nullable();

            $table->foreign('id_usuario_subida_pdf')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_laboratorios', function (Blueprint $table) {

            if (Schema::hasColumn('orden_laboratorios', 'id_usuario_subida_pdf')) {
                $table->dropForeign(['id_usuario_subida_pdf']);
            }

            $table->dropColumn([
                'ruta_pdf_resultado',
                'fecha_subida_pdf',
                'id_usuario_subida_pdf'
            ]);
        });
    }
};
