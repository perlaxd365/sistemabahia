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

    protected $rules = [
        'descripcion' => 'required|string|min:5',
        'monto'       => 'required|numeric|min:0.01',
        'responsable' => 'nullable|string|max:100',
    ];

    public function guardar()
    {
        $this->validate(); //verificar turno y pago
        $this->cajaTurno = CajaTurno::turnoAbierto()->first();

        if (!$this->cajaTurno) {
            $this->dispatch(
                'alert',
                ['type' => 'error', 'title' => 'Ya existe caja abierta', 'message' => 'Caja']
            );
            return;
        }
        try {
            DB::transaction(function () {

                // 🔐 Turno abierto obligatorio

                // 1️⃣ Caja chica
                $cajaChica = ModelsCajaChica::create([
                    'id_caja_turno'     => $this->cajaTurno->id_caja_turno,
                    'descripcion'       => $this->descripcion,
                    'monto'             => $this->monto,
                    'responsable'       => $this->responsable,
                    'fecha_movimiento'  => now(),
                    'user_id'           => auth()->id(),
                ]);

                // 2️⃣ Movimiento automático (EGRESO)
                CajaMovimiento::create([
                    'id_caja_turno'    => $this->cajaTurno->id_caja_turno,
                    'id_usuario'       => auth()->id(),
                    'id_referencia'    => $cajaChica->id_caja_chica,
                    'tabla_referencia' => 'caja_chicas',
                    'tipo'             => 'EGRESO',
                    'descripcion'      => $cajaChica->descripcion,
                    'monto'            => $cajaChica->monto,
                    'responsable'      => $cajaChica->responsable,
                ]);
            });

            // Limpiar formulario
            $this->reset(['descripcion', 'monto', 'responsable']);

            $this->dispatch(
                'alert',
                [
                    'type' => 'success',
                    'title' => 'Caja chica registrada',
                    'message' => 'El egreso fue registrado correctamente.'
                ]
            );
        } catch (\Throwable $th) {
            dd($th);
        }


        // Para refrescar listados
        //$this->dispatch('cajaChicaActualizada');
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
            ? ModelsCajaChica::where('id_caja_turno', $cajaTurno->id_caja_turno)
            ->latest()
            ->get()
            : collect();
        return view('livewire.caja.caja-chica', compact('registros'));
    }
}
