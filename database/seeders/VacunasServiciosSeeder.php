<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VacunasServiciosSeeder extends Seeder
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
            ['nombre_tipo_servicio' => 'VACUNAS'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPO
        // ============================

        $subtipo = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'INMUNIZACIONES',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // SERVICIOS
        // ============================

        $servicios = [

            ['VACUNA ANTITETANICA', 140],
            ['VACUNA ANTINEUMOCOCICA 13', 300],
            ['VACUNA TETRAVALENTE PARA INFLUENZA', 125],
            ['VACUNA VIRUS PAPILOMA HUMANO', 1325],
            ['VACUNA ANTIRRABICA', 350],
            ['VACUNA ANTINEUMOCOCICA 23', 200],
            ['VACUNA HEPATITIS A', 180],
            ['VACUNA HEPATITIS B', 150],
            ['VACUNA BCG + HEPATITIS B', 150],

        ];

        foreach ($servicios as $srv) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $srv[0],
                    'id_subtipo_servicio' => $subtipo->id_subtipo_servicio
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
