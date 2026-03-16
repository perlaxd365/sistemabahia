<?php

namespace Database\Seeders;

use App\Models\Infraestructura;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfraestructuraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Infraestructura::create([

            'codigo_ipress' => '00016536',
            'codigo_ugipress' => '00016536',

            'consultorios_fisicos' => 5,
            'consultorios_funcionales' => 15,
            'camas_hospitalarias' => 8,

            'total_medicos' => 47,
            'medicos_serums' => 0,
            'medicos_residentes' => 0,

            'enfermeras' => 4,
            'odontologos' => 1,
            'psicologos' => 2,
            'nutricionistas' => 1,
            'tecnologos_medicos' => 2,
            'obstetrices' => 1,
            'farmaceuticos' => 1,

            'auxiliares_tecnicos' => 4,
            'otros_profesionales' => 2,

            'ambulancias' => 0

        ]);
    }
}
