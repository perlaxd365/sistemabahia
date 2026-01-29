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
        $this->call(ImagenAreasSeeder::class);
        $this->call(ImagenEstudiosSeeder::class);

        // =========================
        // SERVICIOS CLÍNICOS
        // =========================

        $this->call(EcografiasServiciosSeeder::class);
        $this->call(RayosXServiciosSeeder::class);
        $this->call(TomografiaServiciosSeeder::class);
        $this->call(EndoscopiaServiciosSeeder::class);
        $this->call(PatologiaServiciosSeeder::class);
        $this->call(VacunasServiciosSeeder::class);
        $this->call(PartosServiciosSeeder::class);
        $this->call(CirugiasServiciosSeeder::class);
        $this->call(AlquilerSalaCirugiaServiciosSeeder::class);

        // =========================
        // FACTURACIÓN
        // =========================

        $this->call(NubeFactSeeder::class);
        $this->call(SeriesSeeder::class);
    }
}
