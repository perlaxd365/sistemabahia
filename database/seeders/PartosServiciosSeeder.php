<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartosServiciosSeeder extends Seeder
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
            ['nombre_tipo_servicio' => 'PARTOS'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPOS
        // ============================

        $subNatural = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'PARTO NATURAL',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        $subCesarea = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'PARTO CESAREA',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // PARTO NATURAL
        // ============================

        $naturales = [
            ['PARTO NATURAL 1ER TRIMESTRE', 3400],
            ['PARTO NATURAL 2DO TRIMESTRE', 3200],
            ['PARTO NATURAL 3ER TRIMESTRE', 2900],
            ['PARTO NATURAL DIRECTO', 2500],
        ];

        foreach ($naturales as $srv) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $srv[0],
                    'id_subtipo_servicio' => $subNatural->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $srv[1],
                    'estado_servicio' => true,
                    'codigo_sunat' => null,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }

        // ============================
        // PARTO CESAREA
        // ============================

        $cesareas = [
            ['PARTO CESAREA 1ER TRIMESTRE', 4700],
            ['PARTO CESAREA 2DO TRIMESTRE', 4400],
            ['PARTO CESAREA 3ER TRIMESTRE', 4200],
            ['PARTO CESAREA DIRECTO', 3800],
        ];

        foreach ($cesareas as $srv) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $srv[0],
                    'id_subtipo_servicio' => $subCesarea->id_subtipo_servicio
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
