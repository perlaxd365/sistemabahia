<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboratorioServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================
        // TIPO SERVICIO
        // ============================

        $tipo = TipoServicio::firstOrCreate(
            ['nombre_tipo_servicio' => 'LABORATORIO'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPO SERVICIO
        // ============================

        $subtipo = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'ANÁLISIS DE LABORATORIO',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            [
                'estado_subtipo_servicio' => true
            ]
        );

        // ============================
        // SERVICIOS
        // ============================

        $servicios = [

            ['ACIDO URICO', 15],
            ['AFP', 80],
            ['AGA + ELECTROLITOS', 350],
            ['AGA(GASES ARTERIALES)', 250],
            ['AGLUTINACIONES', 50],
            ['AMILASA', 50],
            ['ANA (RESULTADOS 3 DIAS)', 100],
            ['ANTI DNA', 120],
            ['ASO', 20],
            ['BILIRRUBINAS TOTALES Y FRACCIONADAS', 30],
            ['BK ESPUTO (I MX)', 25],
            ['CA 125', 80],
            ['CA 19.9', 80],
            ['CALCIO', 60],
            ['COCAINA', 50],
            ['COLESTEROL HDL', 20],
            ['COLESTEROL LDL', 20],
            ['COLESTEROL TOTAL', 20],
            ['COLESTEROL VLDL', 20],
            ['COMPLEMENTO C3', 60],
            ['COMPLEMENTO C4', 60],

            // antibio -> precio 0 (editable luego)
            ['COPROCULTIVO', 0],

            ['CPK MB (ENCIMA MUSCULAR - CORAZÓN)', 80],
            ['CPK TOTAL (ENCIMA MUSCULAR)', 90],
            ['CREATININA', 15],
            ['PRUEBA COVID 19', 80],
            ['CULTIVO DE SECRECION FARINGEA', 60],
            ['CULTIVO DE SECRECION VAGINAL', 60],
            ['DENGUE IGM PRUEBA RAPIDA', 70],
            ['DESHIDROGENASA LACTICA (LDH)', 50],
            ['DIMERO D', 80],
            ['ELECTROLITOS SANGRE', 120],
            ['ESPERMACULTIVO', 80],
            ['ESPERMATOGRAMA (7 DIAS DE ABSTINENCIA)', 95],
            ['ESTRADIOL', 60],
            ['EXAMEN TOXICOLOGICO', 150],
            ['FACTOR REUMATOIDEO (FR LATEX)', 80],
            ['FERRITINA', 70],
            ['FIBRINOGENO', 50],
            ['FOSFATASA ALCALINA', 30],
            ['FOSFORO', 30],
            ['FOSFORO (ORINA 24 HORAS)', 50],
            ['FSH', 60],
            ['GLUCOSA O GLICEMIA', 15],
            ['GRUPO SANGUINEO + FACTOR RH', 20],
            ['HCG BETA CUANTITATIVA', 90],
            ['HELICOBACTER PYLORI - PRUEBA RAPIDA', 50],
            ['HEMOGLOBINA + HEMATOCRITO', 15],
            ['HEMOGLOBINA GLICOSILADA (HBA1C)', 60],
            ['HEMOGRAMA AUTOMATIZADO', 70],
            ['HEMOGRAMA COMPLETO', 70],
            ['HEPATITIS A IGM', 80],
            ['HEPATITIS B ANTIGENO SUPERFICIE', 60],
            ['HIERRO', 50],
            ['HIV RAPIDO TEST', 50],
            ['HORMONA ANTIMULLERIANA (HAM)', 340],
            ['INMUNOGLOBULINA E', 80],
            ['LAMINA PERIFERICA', 90],
            ['LH', 60],
            ['LIPASA', 80],
            ['MARIHUANA', 50],
            ['ORINA COMPLETA + GRAM', 27],
            ['PANEL DE ALERGIAS', 350],
            ['PARASITOS (1 MUESTRA)', 20],
            ['PARASITOS SERIADO (3 MUESTRAS)', 40],
            ['PERFIL DE COAGULACION', 180],
            ['PERFIL HEPATICO', 120],
            ['PERFIL LIPIDICO', 70],
            ['PERFIL RENAL', 200],
            ['PROGESTERONA', 90],
            ['PROLACTINA', 60],
            ['PROTEINA C REACTIVA (PCR LATEX)', 60],
            ['PROTEINAS TOTALES Y FRACCIONADAS', 30],
            ['PROTEINURIA ORINA 24 H', 50],
            ['PRUEBA DE ADN', 2000],
            ['PRUEBA DE PARCHE', 10],
            ['PSA LIBRE', 80],
            ['PSA TOTAL', 50],
            ['REACCION INFLAMATORIA', 30],
            ['RETICULOCITOS', 60],
            ['RPR', 50],
            ['T3 LIBRE', 50],
            ['TSH', 50],
            ['T4 LIBRE', 60],
            ['TIEMPO DE COAGULACION', 15],
            ['TIEMPO DE PROTROMBINA (TP)', 60],
            ['TIEMPO DE SANGRIA', 15],
            ['TIEMPO DE TROMBOPLASTINA (TTP)', 50],
            ['TOLERANCIA A LA GLUCOSA', 120],
            ['TORCH IGM', 400],
            ['TRANSAMINASA TGO - TGP', 40],
            ['TRIGLICERIDOS', 15],
            ['TROPONINA I', 120],
            ['TROPONINA T', 160],
            ['UREA', 15],
            ['UROCULTIVO', 80],
            ['VDRL', 50],
            ['VELOCIDAD DE SEDIMENTACION (VSG)', 40],
            ['VITAMINA B12', 80],
            ['VITAMINA D', 450],
            ['VITAMINA K', 550],
        ];

        foreach ($servicios as $item) {

            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subtipo->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'codigo_sunat' => null,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }
    }
}
