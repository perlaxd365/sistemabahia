<?php

namespace Database\Seeders;

use App\Models\Ipress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IpressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ipress::create([
            'renipress' => '00016536',
            'ruc' => '20531917970',
            'razon_social' => 'CLINICA BAHIA S.A.C.',
            'nombre_comercial' => 'CLINICA BAHIA'
        ]);
    }
}
