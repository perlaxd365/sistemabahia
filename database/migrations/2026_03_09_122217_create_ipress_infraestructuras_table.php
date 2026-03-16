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
        Schema::create('ipress_infraestructuras', function (Blueprint $table) {
            $table->id('id_infraestructura');

            $table->string('codigo_ipress');
            $table->string('codigo_ugipress');

            $table->integer('consultorios_fisicos')->default(0);
            $table->integer('consultorios_funcionales')->default(0);
            $table->integer('camas_hospitalarias')->default(0);

            $table->integer('total_medicos')->default(0);
            $table->integer('medicos_serums')->default(0);
            $table->integer('medicos_residentes')->default(0);

            $table->integer('enfermeras')->default(0);
            $table->integer('odontologos')->default(0);
            $table->integer('psicologos')->default(0);
            $table->integer('nutricionistas')->default(0);
            $table->integer('tecnologos_medicos')->default(0);
            $table->integer('obstetrices')->default(0);
            $table->integer('farmaceuticos')->default(0);

            $table->integer('auxiliares_tecnicos')->default(0);
            $table->integer('otros_profesionales')->default(0);

            $table->integer('ambulancias')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipress_infraestructuras');
    }
};
