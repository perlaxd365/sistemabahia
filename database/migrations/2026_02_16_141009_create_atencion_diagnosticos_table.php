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
        Schema::create('atencion_diagnosticos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_atencion');
            $table->unsignedBigInteger('id_cie10');

            $table->enum('tipo', ['PRINCIPAL', 'SECUNDARIO'])
                ->default('SECUNDARIO');

            $table->timestamps();

            $table->foreign('id_atencion')
                ->references('id_atencion')
                ->on('atencions')
                ->onDelete('cascade');

            $table->foreign('id_cie10')
                ->references('id')
                ->on('cie10');

            $table->unique(['id_atencion', 'id_cie10']);
            $table->index(['id_atencion', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencion_diagnosticos');
    }
};
