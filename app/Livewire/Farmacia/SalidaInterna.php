<?php

namespace App\Livewire\Farmacia;

use App\Models\FarmaciaSalidaInterna;
use App\Models\KardexMedicamento;
use App\Models\Medicamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalidaInterna extends Component
{
    public $buscar = '';
    public $id_medicamento;
    public $cantidad;
    public $motivo;
    public $area;
    public $observacion;
    public $medicamentos;
    protected $rules = [
        'id_medicamento' => 'required',
        'cantidad'       => 'required|numeric|min:0.01',
        'motivo'         => 'required',
        'area'           => 'nullable',
        'observacion'    => 'nullable|max:255',
    ];
    public $medicamentoSeleccionado;
    public function seleccionarMedicamento($id, $nombre)
    {
        $this->medicamentoSeleccionado = Medicamento::find($id);
        $this->id_medicamento = $this->medicamentoSeleccionado->id_medicamento;
        // 🔴 CLAVE: limpiar buscador y resultados
        $this->buscar = '';
        $this->medicamentos = [];

        $this->cantidad = 1;
    }
    public function medicamentoVencido()
    {
        $fecha = $this->medicamentoSeleccionado?->fecha_vencimiento;

        if (empty($fecha)) {
            return false;
        }

        return \Carbon\Carbon::createFromFormat('m/Y', $fecha)
            ->endOfMonth()
            ->lt(now());
    }
    public function guardar()
    {
        $this->validate();

        DB::transaction(function () {

            $medicamento = Medicamento::lockForUpdate()
                ->where('id_medicamento', $this->id_medicamento)
                ->firstOrFail();

            // 🔒 Validar stock
            if ($medicamento->stock < $this->cantidad) {
                throw new \Exception('Stock insuficiente');
            }
            $stockAnterior = $medicamento->stock;
            $stockNuevo = $stockAnterior - $this->cantidad;

            // 1️⃣ Registrar salida interna
            $salida = FarmaciaSalidaInterna::create([
                'fecha'          => now(),
                'id_medicamento' => $this->id_medicamento,
                'cantidad'       => $this->cantidad,
                'motivo'         => $this->motivo,
                'area'           => $this->area,
                'observacion'    => $this->observacion,
                'id_usuario'     => Auth::id(),
            ]);

            // 2️⃣ Registrar KARDEX
            DB::table('kardex_medicamentos')->insert([
                'id_medicamento'  => $medicamento->id_medicamento,
                'id_compra'       => null,
                'id_atencion'     => null,
                'tipo_movimiento' => 'SALIDA',
                'cantidad'        => $this->cantidad,
                'stock_anterior'  => $stockAnterior,
                'stock_actual'    => $stockNuevo,
                'descripcion'     => 'Salida interna farmacia - ' . strtoupper($this->motivo),
                'user_id'         => Auth::id(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 3️⃣ Actualizar stock del medicamento
            $medicamento->update([
                'stock' => $stockNuevo
            ]);
        });

        $this->reset(['buscar', 'id_medicamento', 'cantidad', 'motivo', 'area', 'observacion']);

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Salida interna registrada y stock actualizado', 'message' => 'Exito']
        );
    }

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $buscar_lista = '';

    public function render()
    {

        if (strlen($this->buscar) >= 2) {
            $this->medicamentos = Medicamento::where(function ($q) {
                $q->where('nombre', 'like', "%{$this->buscar}%")
                    ->orWhere('presentacion', 'like', "%{$this->buscar}%")
                    ->orWhere('marca', 'like', "%{$this->buscar}%");
            })
                ->orderBy('nombre')
                ->limit(10)
                ->get();
        }
        $medicamentos = $this->medicamentos;

        $salidas = KardexMedicamento::with('medicamento', 'user')
            ->where('tipo_movimiento', 'SALIDA')
            ->whereNull('id_atencion')
            ->where(function ($query) {
                $query
                    ->whereHas('medicamento', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->buscar_lista . '%');
                    })
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->buscar_lista . '%');
                    });
            })
            ->latest()
            ->paginate(10);
        return view('livewire.farmacia.salida-interna', compact('medicamentos', 'salidas'));
    }
}
