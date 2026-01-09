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
        Schema::create('orden_imagenes', function (Blueprint $table) {
            $table->bigIncrements('id_orden_imagen');
            $table->unsignedBigInteger('id_atencion');
            $table->date('fecha');
            $table->text('diagnostico')->nullable();
            $table->string('solicitante')->nullable();
            $table->string('profesional')->nullable();
            $table->enum('estado', ['PENDIENTE', 'INFORMADO'])->default('PENDIENTE');
            $table->timestamps();

            $table->foreign('id_atencion')
                ->references('id_atencion')
                ->on('atencions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_imagenes');
    }
};
