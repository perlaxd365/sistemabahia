<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratorioAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('laboratorio_areas')->insert([
            ['nombre' => 'Bioquímica', 'codigo' => 'BIO'],
            ['nombre' => 'Perfil Lipídico', 'codigo' => 'LIP'],
            ['nombre' => 'Perfil Hepático', 'codigo' => 'HEP'],
            ['nombre' => 'Perfil Renal', 'codigo' => 'REN'],
            ['nombre' => 'Hematología', 'codigo' => 'HEMA'],
            ['nombre' => 'Perfil de Coagulación', 'codigo' => 'COAG'],
            ['nombre' => 'Inmunología', 'codigo' => 'INMU'],
            ['nombre' => 'Microbiología', 'codigo' => 'MICRO'],
            ['nombre' => 'Cultivo', 'codigo' => 'CULT'],
            ['nombre' => 'Heces', 'codigo' => 'HECES'],
            ['nombre' => 'Perfil Coprofuncional', 'codigo' => 'COPRO'],
            ['nombre' => 'Patología - Citología', 'codigo' => 'CITO'],
            ['nombre' => 'Otros', 'codigo' => 'OTROS'],
        ]);
    }
}
