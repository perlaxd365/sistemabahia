<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlquilerSalaCirugiaServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================
        // TIPO
        // ============================

        $tipo = TipoServicio::firstOrCreate(
            ['nombre_tipo_servicio' => 'ALQUILER SALA CIRUGIA'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPO
        // ============================

        $sub = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'PAQUETES SALA QUIRURGICA',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // SERVICIOS
        // ============================

        $servicios = [

            // PROSTATECTOMIA
            ['ALQUILER SALA PROSTATECTOMIA CON ANESTESIA', 1000],
            ['ALQUILER SALA PROSTATECTOMIA SIN ANESTESIA', 700],

            // TRAUMATOLOGIA
            ['ALQUILER SALA TRAUMATOLOGIA CON ANESTESIA', 1000],
            ['ALQUILER SALA TRAUMATOLOGIA SIN ANESTESIA', 700],

            // TRAUMATOLOGIA CLAVICULA
            ['ALQUILER SALA TRAUMATOLOGIA CLAVICULA CON ANESTESIA', 1050],
            ['ALQUILER SALA TRAUMATOLOGIA CLAVICULA SIN ANESTESIA', 700],

            // LEGRADO / LIPOMA / CUERPO EXTRAÑO
            ['ALQUILER SALA LEGRADO LIPOMA CUERPO EXTRAÑO CON ANESTESIA', 350],
            ['ALQUILER SALA LEGRADO LIPOMA CUERPO EXTRAÑO SIN ANESTESIA', 200],

            // QUISTE DE BARTOLINO
            ['ALQUILER SALA QUISTE DE BARTOLINO CON ANESTESIA', 400],
            ['ALQUILER SALA QUISTE DE BARTOLINO SIN ANESTESIA', 200],

        ];

        foreach ($servicios as $srv) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $srv[0],
                    'id_subtipo_servicio' => $sub->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $srv[1],
                    'estado_servicio' => true,
                    'codigo_sunat' => null,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }
    }
}
