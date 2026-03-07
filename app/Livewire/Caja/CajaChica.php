<?php

namespace App\Livewire\Caja;

use App\Models\CajaChica as ModelsCajaChica;
use App\Models\CajaMovimiento;
use App\Models\CajaTurno;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use function Symfony\Component\Clock\now;

class CajaChica extends Component
{
    public $descripcion;
    public $monto;
    public $responsable;
    public $cajaTurno;
    public $tipo = 'EGRESO';
    protected $rules = [
        'tipo'        => 'required|in:INGRESO,EGRESO',
        'descripcion' => 'required|string|min:5',
        'monto'       => 'required|numeric|min:0.01',
        'responsable' => 'nullable|string|max:100',
    ];
    public function guardar()
    {
        $this->validate();

        $this->cajaTurno = CajaTurno::turnoAbierto()->first();

        if (!$this->cajaTurno) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Caja cerrada',
                'message' => 'No existe un turno de caja abierto.'
            ]);
            return;
        }

        DB::transaction(function () {

            // 🔻 EGRESO (Caja chica)
            if ($this->tipo === 'EGRESO') {

                $cajaChica = ModelsCajaChica::create([
                    'id_caja_turno'    => $this->cajaTurno->id_caja_turno,
                    'descripcion'      => $this->descripcion,
                    'monto'            => $this->monto,
                    'responsable'      => $this->responsable,
                    'fecha_movimiento' => now(),
                    'user_id'          => auth()->id(),
                ]);

                CajaMovimiento::create([
                    'id_caja_turno'    => $this->cajaTurno->id_caja_turno,
                    'id_usuario'       => auth()->id(),
                    'id_referencia'    => $cajaChica->id_caja_chica,
                    'tabla_referencia' => 'caja_chicas',
                    'tipo'             => 'EGRESO',
                    'descripcion'      => $this->descripcion,
                    'monto'            => $this->monto,
                    'responsable'      => $this->responsable,
                ]);
            }

            // 🔺 INGRESO manual
            if ($this->tipo === 'INGRESO') {

                CajaMovimiento::create([
                    'id_caja_turno'    => $this->cajaTurno->id_caja_turno,
                    'id_usuario'       => auth()->id(),
                    'id_referencia'    => null,
                    'tabla_referencia' => null,
                    'tipo'             => 'INGRESO',
                    'descripcion'      => $this->descripcion,
                    'monto'            => $this->monto,
                    'responsable'      => $this->responsable,
                ]);
            }
        });

        $this->reset(['descripcion', 'monto', 'responsable']);

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Movimiento registrado',
            'message' => 'El movimiento fue registrado correctamente.'
        ]);
    }

    protected $listeners = ['cajaChicaActualizada' => '$refresh'];

    public function anular($idCajaChica)
    {
        DB::transaction(function () use ($idCajaChica) {

            $cajaChica = ModelsCajaChica::where('id_caja_chica', $idCajaChica)
                ->where('estado', 'REGISTRADO')
                ->firstOrFail();

            // 🔐 Validar turno abierto
            $cajaTurno = CajaTurno::turnoAbierto()->first();

            if (! $cajaTurno || $cajaTurno->id_caja_turno !== $cajaChica->id_caja_turno) {
                throw new \Exception('No se puede anular. Turno cerrado o no corresponde.');
            }

            // 1️⃣ Marcar caja chica como anulada
            $cajaChica->update([
                'estado' => 'ANULADO'
            ]);

            // 2️⃣ Movimiento reversa (INGRESO)
            CajaMovimiento::create([
                'id_caja_turno'    => $cajaChica->id_caja_turno,
                'id_usuario'       => auth()->id(),
                'id_referencia'    => $cajaChica->id_caja_chica,
                'tabla_referencia' => 'caja_chicas',
                'tipo'             => 'INGRESO',
                'descripcion'      => 'Reversa caja chica: ' . $cajaChica->descripcion,
                'monto'            => $cajaChica->monto,
                'responsable'      => auth()->user()->name,
            ]);
        });

        $this->dispatch(
            'alert',
            [
                'type' => 'success',
                'title' => 'Caja chica anulada',
                'message' => 'El egreso fue revertido correctamente.'
            ]
        );

        $this->dispatch('cajaChicaActualizada');
    }

    public function getTotalCajaChicaProperty()
    {
        $cajaTurno = CajaTurno::turnoAbierto()->first();

        if (!$cajaTurno) {
            return 0;
        }
        return ModelsCajaChica::where('id_caja_turno', $cajaTurno->id_caja_turno)
            ->where('estado', 'REGISTRADO')
            ->sum('monto');
    }

    public function render()
    {
        $cajaTurno = CajaTurno::turnoAbierto()->first();

        $registros = $cajaTurno
            ? CajaMovimiento::where('id_caja_turno', $cajaTurno->id_caja_turno)->with('cajaChica')
            ->latest()
            ->get()
            : collect();
        return view('livewire.caja.caja-chica', compact('registros'));
    }
}
