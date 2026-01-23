<?php

namespace App\Livewire\Caja;

use App\Models\CajaMovimiento as ModelsCajaMovimiento;
use App\Models\CajaTurno;
use Livewire\Component;

class CajaMovimiento extends Component
{
    public $cajaTurno;
    public $movimientos;

    public $totalIngresos = 0;
    public $totalEgresos  = 0;
    public $saldo         = 0;

    public function mount()
    {
        $this->cajaTurno = CajaTurno::turnoAbierto()->first();

        if (! $this->cajaTurno) {
            return;
        }

        $this->cargarMovimientos();
    }

    public function cargarMovimientos()
    {
        $this->movimientos = ModelsCajaMovimiento::where('id_caja_turno', $this->cajaTurno->id_caja_turno)
            ->orderBy('created_at')
            ->get();

        $this->totalIngresos = $this->movimientos
            ->where('tipo', 'INGRESO')
            ->sum('monto');

        $this->totalEgresos = $this->movimientos
            ->where('tipo', 'EGRESO')
            ->sum('monto');

        $this->saldo = $this->totalIngresos - $this->totalEgresos;
    }
    public function render()
    {
        return view('livewire.caja.caja-movimiento');
    }
}
