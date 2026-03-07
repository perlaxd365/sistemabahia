<?php

namespace App\Livewire\Index;

use App\Models\Atencion;
use App\Models\CajaMovimiento;
use App\Models\KardexMedicamento;
use App\Models\Medicamento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class IndexAction extends Component
{

    public $search = '';

    use  WithPagination, WithoutUrlPagination;
    protected $paginationTheme = "bootstrap";
    protected $listeners = [
        'atencionActualizada' => '$refresh'
    ];
    public $show;
    public function mount()
    {

        $this->show = 8;
    }

    public function render()
    {


        $atenciones = Atencion::with(['paciente', 'medico', 'comprobantes','consulta'])
            ->where('estado', 'PROCESO')
            ->when(Auth::user()->nombre_cargo === 'Doctor', function ($query) {
                $query->where('id_medico', Auth::id());
            })
            ->whereHas('paciente', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('dni', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->show);

        $totalUsuarios = User::count();
        $activos       = User::where('estado_user', 1)->count();
        $inactivos     = User::where('estado_user', 0)->count();
        $nuevos        = User::where('created_at', '>=', now()->subDays(30))->count();
        $privilegio    = auth()->user()->privilegio_cargo;

        return view('livewire.index.index-action', compact(
            'totalUsuarios',
            'activos',
            'inactivos',
            'nuevos',
            'privilegio',
            'atenciones'
        ));
    }

    public function anular($id)
    {
        DB::transaction(function () use ($id) {

            $atencion = Atencion::with([
                'pagos.cajaTurno',
                'comprobantes',
                'medicamentos'
            ])->findOrFail($id);

            // 1️⃣ VALIDAR COMPROBANTES EMITIDOS
            if ($atencion->comprobantes()->where('estado', 'EMITIDO')->exists()) {
                throw new \Exception(
                    'La atención tiene comprobantes EMITIDOS. Debe generar Nota de Crédito antes de anular.'
                );
            }

            // 2️⃣ VALIDAR CAJA CERRADA
            foreach ($atencion->pagos as $pago) {
                if ($pago->cajaTurno->estado === 'CERRADO') {
                    throw new \Exception('No se puede anular. Turno de caja cerrado.');
                }
            }

            // 3️⃣ CAMBIAR ESTADO ATENCIÓN
            $atencion->estado = 'ANULADO';
            $atencion->save();

            // 4️⃣ ANULAR PAGOS + REVERSA EN CAJA
            foreach ($atencion->pagos as $pago) {

                $pago->estado = 'ANULADO';
                $pago->save();

                CajaMovimiento::create([
                    'id_caja_turno' => $pago->id_caja_turno,
                    'id_usuario'    => auth()->id(),
                    'tipo'          => 'EGRESO',
                    'descripcion'   => 'ANULACIÓN ATENCIÓN #' . $atencion->id_atencion,
                    'monto'         => $pago->monto,
                ]);
            }

            // 5️⃣ DEVOLVER MEDICAMENTOS
            foreach ($atencion->medicamentos as $item) {

                $medicamento = Medicamento::find($item->id_medicamento);

                $stockAnterior = $medicamento->stock;
                $stockNuevo    = $stockAnterior + $item->pivot->cantidad;

                $medicamento->update([
                    'stock' => $stockNuevo
                ]);

                KardexMedicamento::create([
                    'id_medicamento' => $medicamento->id_medicamento,
                    'id_atencion'    => $atencion->id_atencion,
                    'tipo_movimiento' => 'ENTRADA',
                    'stock_anterior' => $stockAnterior,
                    'cantidad'       => $item->pivot->cantidad,
                    'stock_actual'   => $stockNuevo,
                    'descripcion'    => 'DEVOLUCIÓN POR ANULACIÓN ATENCIÓN #' . $atencion->id_atencion,
                    'user_id'        => auth()->id(),
                ]);
            }

            // 6️⃣ ANULAR COMPROBANTES NO EMITIDOS
            $atencion->comprobantes()
                ->whereNotIn('estado', ['EMITIDO'])
                ->update(['estado' => 'ANULADO']);
        });

        $this->dispatch('alert', [
            'type'    => 'success',
            'title'   => 'Atención anulada correctamente',
            'message' => 'Stock, caja y pagos fueron revertidos correctamente'
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
