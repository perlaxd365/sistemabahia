<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atencion_procedimientos', function (Blueprint $table) {

            // Agregar la nueva FK
            if (!Schema::hasColumn('atencion_procedimientos', 'id_catalogo_procedimiento')) {

                $table->unsignedBigInteger('id_catalogo_procedimiento')
                    ->nullable()
                    ->after('id_atencion');
            }
        });

        // Si aún no hay datos puedes omitir esta parte.
        // Si hay datos, aquí posteriormente haremos la migración
        // desde codigo_procedimiento hacia id_catalogo_procedimiento.

        Schema::table('atencion_procedimientos', function (Blueprint $table) {

            if (Schema::hasColumn('atencion_procedimientos', 'id_catalogo_procedimiento')) {

                $table->foreign('id_catalogo_procedimiento')
                    ->references('id')
                    ->on('catalogo_procedimientos')
                    ->cascadeOnDelete();
            }
        });

        Schema::table('atencion_procedimientos', function (Blueprint $table) {

            if (Schema::hasColumn('atencion_procedimientos', 'codigo_procedimiento')) {
                $table->dropColumn('codigo_procedimiento');
            }

            if (Schema::hasColumn('atencion_procedimientos', 'descripcion')) {
                $table->dropColumn('descripcion');
            }

            if (Schema::hasColumn('atencion_procedimientos', 'codigo_ups')) {
                $table->dropColumn('codigo_ups');
            }

            if (Schema::hasColumn('atencion_procedimientos', 'nombre_ups')) {
                $table->dropColumn('nombre_ups');
            }
        });
    }

    public function down(): void
    {
        Schema::table('atencion_procedimientos', function (Blueprint $table) {

            $table->string('codigo_procedimiento', 20)->nullable();

            $table->string('descripcion')->nullable();

            $table->string('codigo_ups', 10)->nullable();

            $table->string('nombre_ups')->nullable();

            $table->dropForeign(['id_catalogo_procedimiento']);

            $table->dropColumn('id_catalogo_procedimiento');
        });
    }
};
