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
        if (!Schema::hasColumn('atencion_servicios', 'facturado')) {

            Schema::table('atencion_servicios', function (Blueprint $table) {

                $table->boolean('facturado')
                    ->default(false)
                    ->after('estado');
            });
        }

        if (!Schema::hasColumn('atencion_medicamentos', 'facturado')) {

            Schema::table('atencion_medicamentos', function (Blueprint $table) {

                $table->boolean('facturado')
                    ->default(false)
                    ->after('subtotal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('atencion_servicios', 'facturado')) {

            Schema::table('atencion_servicios', function (Blueprint $table) {

                $table->dropColumn('facturado');
            });
        }

        if (Schema::hasColumn('atencion_medicamentos', 'facturado')) {

            Schema::table('atencion_medicamentos', function (Blueprint $table) {

                $table->dropColumn('facturado');
            });
        }
    }
};
