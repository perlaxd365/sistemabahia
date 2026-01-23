<?php

namespace App\Livewire\Sunat;

use App\Models\ResumenDiario;
use App\Services\NubeFactService;
use Livewire\Component;

class ConsultarTicket extends Component
{

    public $ticket;
    public $resultado;
    public $cargando = false;

    protected $rules = [
        'ticket' => 'required|string'
    ];

    public function consultar()
    {
        $this->validate();
        $this->cargando = true;
        $this->resultado = null;

        $resumen = ResumenDiario::where('ticket', $this->ticket)->first();

        if (! $resumen) {
            $this->addError('ticket', 'El ticket no existe en el sistema.');
            $this->cargando = false;
            return;
        }

        $service = app(NubeFactService::class);
        $response = $service->consultarTicket($this->ticket);

        $this->resultado = $response;

        // 🔄 Actualizar estado del resumen
        if (isset($response['estado'])) {
            $resumen->update([
                'estado'   => $response['estado'],
                'cdr_url'  => $response['enlace_del_cdr'] ?? null,
            ]);
        }

        $this->cargando = false;
    }

    public function render()
    {
        return view('livewire.sunat.consultar-ticket');
    }
}
