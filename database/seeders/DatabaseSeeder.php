<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call(UserSeeder::class);
        $this->call(LaboratorioAreasSeeder::class);
        $this->call(LaboratorioExamenesSeeder::class);
        $this->call(LaboratorioRayosSeeder::class);
        $this->call(ImagenAreasSeeder::class);
        $this->call(ImagenEstudiosSeeder::class);
    }
}
