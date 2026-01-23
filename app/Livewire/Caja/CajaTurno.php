<?php

namespace App\Livewire\Caja;

use App\Models\CajaTurno as ModelsCajaTurno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CajaTurno extends Component
{
    public $cajaTurno;
    public $monto_apertura;
    public $monto_cierre;

    public function mount()
    {
        $this->cajaTurno = ModelsCajaTurno::abierto()
            ->where('id_usuario_apertura', Auth()->id())
            ->first();
    }

    /* =====================
     | ABRIR CAJA
     ===================== */
    public function abrirCaja()
    {
        $this->validate([
            'monto_apertura' => 'required|numeric|min:0',
        ]);

        $existe = ModelsCajaTurno::turnoAbierto()
            ->exists();

        if ($existe) {
            $this->dispatch(
                'alert',
                ['type' => 'error', 'title' => 'Ya existe caja abierta', 'message' => 'Caja']
            );
            return;
        }

        // 🔒 Validar si ya existe turno abierto

        DB::transaction(function () {

            $this->cajaTurno =  ModelsCajaTurno::create([
                'fecha_apertura'      => now(),
                'monto_apertura'      => $this->monto_apertura,
                'estado'              => 'ABIERTO',
                'id_usuario_apertura' => Auth::id(),
            ]);
        });



        session(['id_caja_turno' => $this->cajaTurno->id_caja_turno]);
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se aperturó caja con exito', 'message' => 'Exito']
        );
    }

    /* =====================
     | CERRAR CAJA
     ===================== */
    public function cerrarCaja()
    {
        if (!$this->cajaTurno) return;

        $this->cajaTurno->update([
            'fecha_cierre'        => now(),
            'monto_cierre'        => $this->cajaTurno->monto_apertura
                + $this->cajaTurno->totalIngresos()
                - $this->cajaTurno->totalEgresos(),
            'estado'              => 'CERRADO',
            'id_usuario_cierre'   => Auth()->id(),
        ]);
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se cerró caja con exito', 'message' => 'Exito']
        );
        session()->forget('id_caja_turno');
        $this->cajaTurno = null;
    }

    public function render()
    {
        return view('livewire.caja.caja-turno', [
            'totales' => $this->cajaTurno
                ? [
                    'EFECTIVO' => $this->cajaTurno->totalPorTipo('EFECTIVO'),
                    'YAPE'     => $this->cajaTurno->totalPorTipo('YAPE'),
                    'PLIN'     => $this->cajaTurno->totalPorTipo('PLIN'),
                    'TARJETA'  => $this->cajaTurno->totalPorTipo('TARJETA'),
                    'TRANSFERENCIA' => $this->cajaTurno->totalPorTipo('TRANSFERENCIA'),
                ]
                : [],
        ]);
    }
}
