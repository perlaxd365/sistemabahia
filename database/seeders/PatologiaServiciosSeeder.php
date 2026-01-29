<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatologiaServiciosSeeder extends Seeder
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
            ['nombre_tipo_servicio' => 'PATOLOGIA'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPO
        // ============================

        $subtipo = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'ANATOMIA PATOLOGICA',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // SERVICIOS
        // ============================

        $servicios = [

            ['BIOPSIA DE LIPOMA DE CUELLO CABELLUDO', 180],
            ['PAP DE CERVIX', 100],
            ['TUMORACION DE CABEZA (CUERO CABELLUDO)', 160],
            ['BIOPSIA DE ANTRO', 100],
            ['BIOPSIA DE ENDOMETRIO', 200],
            ['BIOPSIA GASTRICA', 100],
            ['BIOPSIA DE COLON', 100],
            ['BIOPSIA BULBO DUODENO D/C ADENOCARCINOMA', 120],
            ['LESION ELEVADA EN COLON ASCENDENTE DISTAL', 100],
            ['TUMORACION EN REGION LUMBAR', 180],
            ['BIOPSIA DE DUODENO Y ANTRO', 160],
            ['TUMORACION PARACERVICAL TRICIAL QUISTICA', 160],
            ['BIOPSIA DE QUISTE DE ANEXO DERECHO', 200],
            ['BIOPSIA TEJIDO ENDOMETRIAL', 160],

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
