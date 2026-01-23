<?php

namespace App\Console\Commands;

use App\Models\Comprobante;
use App\Models\ResumenDiario;
use App\Services\NubeFactService;
use Illuminate\Console\Command;

class EnviarResumenDiario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunat:enviar-resumen-diario {fecha?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fecha = $this->argument('fecha') ?? now()->toDateString();

        // 1️⃣ Evitar duplicados
        if (ResumenDiario::whereDate('fecha', $fecha)->exists()) {
            $this->warn("Ya existe un resumen para {$fecha}");
            return;
        }

        // 2️⃣ Boletas válidas
        $boletas = Comprobante::where('tipo_comprobante', 'BOLETA')
            ->whereDate('fecha_emision', $fecha)
            ->whereNull('id_resumen')
            ->get();

        if ($boletas->isEmpty()) {
            $this->info('No hay boletas pendientes para resumir');
            return;
        }

        // 3️⃣ Crear resumen
        $resumen = ResumenDiario::create([
            'fecha'  => $fecha,
            'estado' => 'PENDIENTE',
        ]);

        // 4️⃣ Enviar a SUNAT
        $response = app(NubeFactService::class)->enviarResumen($boletas, $resumen);

        // 5️⃣ Validar respuesta
        if (!isset($response['ticket'])) {
            $resumen->update([
                'estado' => 'ERROR',
                'respuesta_sunat' => json_encode($response),
            ]);

            $this->error('SUNAT no devolvió ticket');
            return;
        }

        // 6️⃣ Actualizar resumen
        $resumen->update([
            'ticket' => $response['ticket'],
            'estado' => 'ENVIADO',
            'respuesta_sunat' => json_encode($response),
        ]);

        // 7️⃣ Marcar boletas
        Comprobante::whereIn('id', $boletas->pluck('id'))
            ->update(['id_resumen' => $resumen->id]);

        $this->info("Resumen {$fecha} enviado correctamente");
    }
}
