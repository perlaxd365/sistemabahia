<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\AtencionMedicamento;
use App\Models\Consulta;
use App\Models\KardexMedicamento;
use App\Models\Medicamento;
use App\Models\User;
use Exception;
use Livewire\Component;

class Medicamentos extends Component
{

    public $id_atencion;

    public $buscarMedicamento = '';
    public $resultados = [];

    public $detalle = [];

    protected $listeners = ['cargarAtencion'];
    public $tratamiento;
    public $nombre_paciente;
    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $consulta = Consulta::where("id_atencion", $this->id_atencion)->first();

        if ($consulta) {
            $this->tratamiento = $consulta->tratamiento_consulta;
        }
        $this->historial();

        //nombre paciente
        $atencion = Atencion::find($id_atencion);
        $paciente = User::find($atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
    }
    public function render()
    {
        return view('livewire.atencion.medicamentos');
    }


    // 🔍 Buscador
    public function updatedBuscarMedicamento()
    {
        if (strlen($this->buscarMedicamento) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = Medicamento::where('nombre', 'like', '%' . $this->buscarMedicamento . '%')
            ->where('stock', '>', 0)
            ->limit(10)
            ->get();
    }

    // ➕ Agregar medicamento
    public function agregarMedicamento($id_medicamento)
    {
        $med = Medicamento::findOrFail($id_medicamento);

        foreach ($this->detalle as $item) {
            if ($item['id_medicamento'] == $id_medicamento) {
                return;
            }
        }

        $this->detalle[] = [
            'id_medicamento' => $med->id_medicamento,
            'nombre' => $med->nombre,
            'marca' => $med->marca,
            'concentracion' => $med->concentracion,
            'presentacion' => $med->presentacion,
            'cantidad' => 1,
            'precio' => $med->precio_venta,
            'subtotal' => $med->precio_venta,
            'stock' => $med->stock
        ];

        $this->buscarMedicamento = '';
        $this->resultados = [];
    }

    // 🧮 Calcular subtotal en vivo
    public function updatedDetalle()
    {
        foreach ($this->detalle as $i => $item) {

            if ($item['cantidad'] < 1) {
                $this->detalle[$i]['cantidad'] = 1;
            }

            if ($item['cantidad'] > $item['stock']) {
                $this->detalle[$i]['cantidad'] = $item['stock'];
            }

            $this->detalle[$i]['subtotal'] =
                $this->detalle[$i]['cantidad'] * $this->detalle[$i]['precio'];
        }
    }

    // ❌ Quitar medicamento
    public function removeItem($index)
    {
        unset($this->detalle[$index]);
        $this->detalle = array_values($this->detalle);
    }

    // 💾 Guardar y descontar stock
    public function guardarMedicamentos()
    {
        if (!$this->id_atencion || count($this->detalle) == 0) {
            return;
        }

        foreach ($this->detalle as $item) {

            AtencionMedicamento::create([
                'id_atencion' => $this->id_atencion,
                'id_medicamento' => $item['id_medicamento'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio'],
                'subtotal' => $item['subtotal'],
            ]);



            //kardex 
            $medicamento = Medicamento::find($item['id_medicamento']);

            $stockAnterior = $medicamento->stock;

            if ($stockAnterior < $item['cantidad']) {
                throw new Exception('Stock insuficiente');
            }

            $stockNuevo = $stockAnterior - $item['cantidad'];

            // Actualizar stock
            Medicamento::where('id_medicamento', $item['id_medicamento'])
                ->decrement('stock', $item['cantidad']);

            // Registrar kardex
            KardexMedicamento::create([
                'id_medicamento'  => $item['id_medicamento'],
                'id_atencion'     => $this->id_atencion,
                'tipo_movimiento' => 'SALIDA',
                'cantidad'        => $item['cantidad'],
                'stock_anterior'  => $stockAnterior,
                'stock_actual'     => $stockNuevo,
                'descripcion'     => 'Dispensación en atención médica',
                'user_id'         => auth()->user()->id,
            ]);
        }

        $this->detalle = [];

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Medicamentos dispensados',
            'message' => 'El stock fue actualizado correctamente'
        ]);
    }

    public $mostrarDispensados = false;
    public $medicamentosDispensados = [];

    public function toggleDispensados()
    {

        $this->historial();
        $this->mostrarDispensados = ! $this->mostrarDispensados;
    }

    public function historial()
    {
        //dispensados
        $this->medicamentosDispensados = AtencionMedicamento::with('medicamentos')
            ->where('id_atencion', $this->id_atencion)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
