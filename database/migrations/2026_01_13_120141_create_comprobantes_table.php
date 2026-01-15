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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->bigIncrements('id_comprobante');

            // SUNAT / NubeFact
            $table->enum('tipo_comprobante', ['TICKET', 'BOLETA', 'FACTURA', 'NOTA_CREDITO']);
            $table->string('serie', 4);
            $table->integer('numero')->nullable(); // se asigna al emitir

            // Relaciones
            $table->unsignedBigInteger('id_atencion')->nullable();
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_cliente')->nullable(); // factura

            // Fechas
            $table->string('fecha_emision')->nullable();

            // Totales
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            // Fechas
            $table->boolean('con_igv')->nullable()->default(false);
            // Estados
            $table->enum('estado', [
                'BORRADOR',
                'EMITIDO',
                'ANULADO',
                'CERRADO',
                'RECHAZADO'
            ])->default('BORRADOR');

            // Respuesta SUNAT / NubeFact
            $table->string('sunat_codigo')->nullable();        // 0 = aceptado
            $table->text('sunat_descripcion')->nullable();
            $table->string('sunat_hash')->nullable();
            $table->text('sunat_qr')->nullable();
            $table->json('sunat_respuesta')->nullable();
            $table->string('xml_url')->nullable();
            $table->string('cdr_url')->nullable();
            $table->string('pdf_url')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
