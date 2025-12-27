<?php

namespace App\Livewire\Farmacia;

use App\Models\Medicamento;
use App\Models\Proveedor;
use Cloudinary\Asset\Media;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Medicamentos extends Component
{
    public $id_medicamento,
        $nombre,
        $presentacion,
        $concentracion,
        $stock,
        $precio_venta,
        $fecha_vencimiento,
        $marca;
    public $search, $show;
    use  WithPagination, WithoutUrlPagination;
    protected $paginationTheme = "bootstrap";
    public $edicion = false;
    public function render()
    {
        $medicamentos = Medicamento::select('*')
            ->where(function ($query) {
                return $query
                    ->orwhere('nombre', 'LIKE', '%' . $this->search . '%')
                    ->orwhere('presentacion', 'LIKE', '%' . $this->search . '%')
                    ->orwhere('concentracion', 'LIKE', '%' . $this->search . '%')
                    ->orwhere('marca', 'LIKE', '%' . $this->search . '%');
            })
            ->paginate($this->show);
        return view('livewire.farmacia.medicamentos', compact('medicamentos'));
    }

    public function agregar()
    {
        $messages = [
            'nombre.required' =>  "Ingresar nombre de producto",
            'precio_venta.required' =>  "Ingresar el precio de producto",
            'stock.required' =>  "Ingresar stock de producto",
        ];

        $rules = [
            'nombre' => 'required',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',

        ];

        $this->validate($rules, $messages);


        Medicamento::create([
            'nombre' =>  $this->nombre,
            'presentacion' =>  $this->presentacion,
            'concentracion' =>  $this->concentracion,
            'precio_venta' =>  $this->precio_venta,
            'stock' =>  $this->stock,
            'marca' =>  $this->marca,
            'fecha_vencimiento' =>  $this->fecha_vencimiento,
        ]);
        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registro el medicamento correctamente', 'message' => 'Exito']
        );
        $this->default();
    }

    public function default()
    {
        $this->nombre = "";
        $this->presentacion = "";
        $this->concentracion = "";
        $this->precio_venta = "";
        $this->stock = "";
        $this->marca = "";
        $this->fecha_vencimiento = "";
    }


    public function editar($id)
    {
        $medicamento = Medicamento::find($id);
        $this->id_medicamento = $medicamento->id_medicamento;
        $this->edicion = true;
        $this->nombre = $medicamento->nombre;
        $this->presentacion = $medicamento->presentacion;
        $this->concentracion = $medicamento->concentracion;
        $this->precio_venta = $medicamento->precio_venta;
        $this->stock = $medicamento->stock;
        $this->marca = $medicamento->marca;
        $this->fecha_vencimiento = $medicamento->fecha_vencimiento;
    }

    public function actualizar()
    {
        $messages = [
            'nombre.required' =>  "Ingresar nombre de producto",
            'precio_venta.required' =>  "Ingresar el precio de producto",
            'stock.required' =>  "Ingresar stock de producto",
        ];

        $rules = [
            'nombre' => 'required',
            'precio_venta' => 'required',
            'stock' => 'required',

        ];
        $this->validate($rules, $messages);

        $medicamento = Medicamento::find($this->id_medicamento);
        $medicamento->update(
            [
                'nombre' =>  $this->nombre,
                'presentacion' =>  $this->presentacion,
                'concentracion' =>  $this->concentracion,
                'precio_venta' =>  $this->precio_venta,
                'stock' =>  $this->stock,
                'marca' =>  $this->marca,
                'fecha_vencimiento' =>  $this->fecha_vencimiento,
            ]
        );
        $this->edicion = false;
        $this->default();

        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se actualizo el medicamento ' . $medicamento->nombre . ' correctamente', 'message' => 'Exito']
        );
    }

    public function eliminar($id_medicamento)
    {
        $medicamento = Medicamento::find($id_medicamento);
        $medicamento->update([
            'estado' => false
        ]);
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'info', 'title' => 'Se elimino el medicamento ' . $medicamento->nombre . ' correctamente', 'message' => 'Exito']
        );
    }

    public function habilitar($id_medicamento)
    {
        $medicamento = Medicamento::find($id_medicamento);
        $medicamento->update([
            'estado' => true
        ]);
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'info', 'title' => 'Se habilito el medicamento ' . $medicamento->nombre . ' correctamente', 'message' => 'Exito']
        );
    }
}
