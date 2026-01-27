<?php

namespace App\Livewire\Caja;

use App\Models\CajaMovimiento as ModelsCajaMovimiento;
use App\Models\CajaTurno;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class CajaMovimiento extends Component
{
    public $cajaTurno;

    public $movimientos;
    public $usuarios;

    public $fechaConsulta;
    public $idUsuario;

    public $totalIngresos = 0;
    public $totalEgresos  = 0;
    public $saldo         = 0;

    public function mount()
    {
        // 👉 por defecto HOY
        $this->fechaConsulta = Carbon::now()->toDateString();

        // Turno activo (si existe)
        $this->cajaTurno = CajaTurno::turnoAbierto()->first();

        // Usuarios para filtro
        $this->usuarios = User::where('privilegio_cargo',5)->orderBy('name')->get();

        $this->cargarMovimientos();
    }

    public function cargarMovimientos()
    {
        $query = ModelsCajaMovimiento::query()
            ->with('usuario'); // si tienes relación user

        // 📅 Filtro por fecha
        if ($this->fechaConsulta) {
            $query->whereDate('created_at', $this->fechaConsulta);
        }

        // 👤 Filtro por persona
        if ($this->idUsuario) {
            $query->where('id_usuario', $this->idUsuario);
        }

        // 🔁 Orden cronológico
        $this->movimientos = $query
            ->orderBy('created_at')
            ->get();

        // 🔢 Totales
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
