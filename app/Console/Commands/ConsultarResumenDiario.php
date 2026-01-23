<?php

namespace App\Console\Commands;

use App\Models\Comprobante;
use App\Models\ResumenDiario;
use App\Services\NubeFactService;
use Illuminate\Console\Command;

class ConsultarResumenDiario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consultar-resumen-diario';

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
        //
        $resumenes = ResumenDiario::where('estado', 'ENVIADO')->get();

        foreach ($resumenes as $resumen) {
dd("aqui me quede");
            $response = app(NubeFactService::class)
                ->consultarTicket($resumen->ticket);

            if ($response['estado'] === 'ACEPTADO') {

                $resumen->update([
                    'estado' => 'ACEPTADO',
                    'respuesta_sunat' => json_encode($response),
                ]);

                Comprobante::where('tipo_comprobante', 'BOLETA')
                    ->whereDate('fecha_emision', $resumen->fecha)
                    ->update([
                        'sunat_codigo' => 0,
                        'sunat_descripcion' => 'Aceptado por resumen diario'
                    ]);
            }
        }
    }
}
