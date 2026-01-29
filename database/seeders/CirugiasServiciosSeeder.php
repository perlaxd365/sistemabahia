<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CirugiasServiciosSeeder extends Seeder
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
            ['nombre_tipo_servicio' => 'CIRUGIAS'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPO
        // ============================

        $sub = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'CIRUGIAS GENERALES',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // SERVICIOS
        // ============================

        $servicios = [

            ['APENDICITIS', 2500],
            ['APENDICITIS LAPAROSCOPICA', 4500],
            ['HEMORROIDES', 1800],
            ['ABSCESO PERIANAL', 1800],
            ['HERNIA', 2500],
            ['HISTERECTOMIA', 3800],
            ['LEGRADO SIN PREPARACION', 1000],
            ['LEGRADO CON PREPARACION', 1500],
            ['APENDICITIS COMPLICADA PERITONITIS', 3000],
            ['PROSTATA', 3800],
            ['VESICULA', 3500],
            ['VESICULA LAPAROSCOPICA', 4500],

            // (estos también estaban en partos, pero los dejamos si cirugía también los factura)
            ['PARTO NATURAL CIRUGIA', 2500],
            ['CESAREA CIRUGIA', 3800],
            ['CESAREA DOBLE CON PEDIATRA', 4300],

            ['QUISTECTOMIA', 3000],
            ['CIRUGIA TALON DE AQUILES', 2800],
            ['VASECTOMIA', 1500],
            ['CONO LEEP', 2800],

            ['CLAVICULA MATERIAL A1', 5000],
            ['CLAVICULA MATERIAL CONVENCIONAL', 3500],
            ['CIRUGIA MUÑECA', 3500],

            ['LEGRADO BIOPSICO', 1400],
            ['EVENTRACION', 3700],
            ['FISTULECTOMIA ANAL', 1500],
            ['CIRCUNCISION ADULTO UROLOGO', 2000],
            ['EXERESIS TUMOR DE MAMA', 2400],
            ['PROLAPSO', 4200],

            ['ESCERESIS MAS BIOPSIA', 800],
            ['COLOCACION FISTULA HEMODIALISIS', 2500],
            ['CAUTERIZACION CERVICAL', 1600],
        ];

        foreach ($servicios as $srv) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => strtoupper($srv[0]),
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
