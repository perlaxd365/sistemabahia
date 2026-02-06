<?php

namespace Database\Seeders;

use App\Models\LaboratorioArea;
use App\Models\LaboratorioExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratorioExamenesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examenes = [

            // 🧪 BIOQUÍMICA
            ['area' => 'Bioquímica', 'nombre' => 'Glucosa'],
            ['area' => 'Bioquímica', 'nombre' => 'Urea'],
            ['area' => 'Bioquímica', 'nombre' => 'Creatinina'],
            ['area' => 'Bioquímica', 'nombre' => 'Calcio'],
            ['area' => 'Bioquímica', 'nombre' => 'Fósforo'],
            ['area' => 'Bioquímica', 'nombre' => 'Amilasa'],
            ['area' => 'Bioquímica', 'nombre' => 'Lipasa'],
            ['area' => 'Bioquímica', 'nombre' => 'Deshidrogenasa (LDH)'],
            ['area' => 'Bioquímica', 'nombre' => 'Test ADA'],

            // 🩸 PERFIL LIPÍDICO
            ['area' => 'Perfil Lipídico', 'nombre' => 'Colesterol Total'],
            ['area' => 'Perfil Lipídico', 'nombre' => 'Colesterol HDL'],
            ['area' => 'Perfil Lipídico', 'nombre' => 'Colesterol LDL'],
            ['area' => 'Perfil Lipídico', 'nombre' => 'Colesterol VLDL'],
            ['area' => 'Perfil Lipídico', 'nombre' => 'Triglicéridos'],

            // 🧫 PERFIL HEPÁTICO
            ['area' => 'Perfil Hepático', 'nombre' => 'Bilirrubinas T/F'],
            ['area' => 'Perfil Hepático', 'nombre' => 'Transaminasas (TGO - TGP)'],
            ['area' => 'Perfil Hepático', 'nombre' => 'Fosfatasa Alcalina'],
            ['area' => 'Perfil Hepático', 'nombre' => 'G.G.T.'],
            ['area' => 'Perfil Hepático', 'nombre' => 'Proteínas T/F'],

            // 🚰 PERFIL RENAL
            ['area' => 'Perfil Renal', 'nombre' => 'Electrolitos en sangre'],
            ['area' => 'Perfil Renal', 'nombre' => 'Urocultivo'],
            ['area' => 'Perfil Renal', 'nombre' => 'Depuración de Creatinina'],
            ['area' => 'Perfil Renal', 'nombre' => 'Proteinuria en orina 24h'],
            ['area' => 'Perfil Renal', 'nombre' => 'Ácido Úrico'],
            ['area' => 'Perfil Renal', 'nombre' => 'Microalbuminuria'],
            ['area' => 'Perfil Renal', 'nombre' => 'Creatinina'],

            // 🧬 HEMATOLOGÍA
            ['area' => 'Hematología', 'nombre' => 'Hb / Hto'],
            ['area' => 'Hematología', 'nombre' => 'Hemograma Completo'],
            ['area' => 'Hematología', 'nombre' => 'Hemograma Lam. Periférica'],
            ['area' => 'Hematología', 'nombre' => 'Hemograma Gota Gruesa'],
            ['area' => 'Hematología', 'nombre' => 'Reticulocitos'],
            ['area' => 'Hematología', 'nombre' => 'Velocidad de Sedimentación'],
            ['area' => 'Hematología', 'nombre' => 'Recuento de Eosinófilos'],
            ['area' => 'Hematología', 'nombre' => 'Grupo Sanguíneo'],
            ['area' => 'Hematología', 'nombre' => 'Factor RH'],

            // 🩸 PERFIL COAGULACIÓN
            ['area' => 'Perfil de Coagulación', 'nombre' => 'Tiempo de Coagulación'],
            ['area' => 'Perfil de Coagulación', 'nombre' => 'Tiempo de Sangría'],
            ['area' => 'Perfil de Coagulación', 'nombre' => 'Tiempo de Protrombina'],
            ['area' => 'Perfil de Coagulación', 'nombre' => 'Tiempo P. Tromboplastina'],
            ['area' => 'Perfil de Coagulación', 'nombre' => 'Fibrinógeno'],

            // 🧪 INMUNOLOGÍA
            ['area' => 'Inmunología', 'nombre' => 'RPR - VDRL'],
            ['area' => 'Inmunología', 'nombre' => 'HIV Rapid Test'],
            ['area' => 'Inmunología', 'nombre' => 'PCR Látex'],
            ['area' => 'Inmunología', 'nombre' => 'FR Látex'],
            ['area' => 'Inmunología', 'nombre' => 'Antiestreptolisina O'],
            ['area' => 'Inmunología', 'nombre' => 'Aglutinaciones'],

            // 🦠 MICROBIOLOGÍA
            ['area' => 'Microbiología', 'nombre' => 'Secreción Vaginal Ex. Directo'],
            ['area' => 'Microbiología', 'nombre' => 'BK Ex. Directo'],
            ['area' => 'Microbiología', 'nombre' => 'Raspado de Piel'],
            ['area' => 'Microbiología', 'nombre' => 'Orina + Gram'],
            ['area' => 'Microbiología', 'nombre' => 'Prueba de Helecho'],

            // 🧫 CULTIVO
            ['area' => 'Cultivo', 'nombre' => 'Hemocultivo'],
            ['area' => 'Cultivo', 'nombre' => 'Urocultivo'],
            ['area' => 'Cultivo', 'nombre' => 'Coprocultivo'],
            ['area' => 'Cultivo', 'nombre' => 'Secreción Faríngea'],
            ['area' => 'Cultivo', 'nombre' => 'Secreción Vaginal'],
            ['area' => 'Cultivo', 'nombre' => 'Secreción Prostática'],
            ['area' => 'Cultivo', 'nombre' => 'Secreción Ótica'],
            ['area' => 'Cultivo', 'nombre' => 'Esputo (gérmenes comunes)'],

            // 💩 HECES
            ['area' => 'Heces', 'nombre' => 'Parasitológico'],
            ['area' => 'Heces', 'nombre' => 'Thevenon'],

            // 🧪 COPROFUNCIONAL
            ['area' => 'Perfil Coprofuncional', 'nombre' => 'PH'],
            ['area' => 'Perfil Coprofuncional', 'nombre' => 'Sustancias Reductoras'],
            ['area' => 'Perfil Coprofuncional', 'nombre' => 'Reacciones Inflamatorias'],
            ['area' => 'Perfil Coprofuncional', 'nombre' => 'Sudan III'],

            // 🧬 CITOLOGÍA
            ['area' => 'Patología - Citología', 'nombre' => 'PAP Cervical'],
            ['area' => 'Patología - Citología', 'nombre' => 'PAP'],
            ['area' => 'Patología - Citología', 'nombre' => 'Biopsia'],

            // 📋 OTROS
            ['area' => 'Otros', 'nombre' => 'Examen de Orina Completa'],
            ['area' => 'Otros', 'nombre' => 'Prueba de Parche'],
        ];

        $areas = DB::table('laboratorio_areas')->pluck('id_area', 'nombre');

        foreach ($examenes as $examen) {
            DB::table('laboratorio_examens')->insert([
                'id_area' => $areas[$examen['area']],
                'nombre' => $examen['nombre'],
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


    }
}
