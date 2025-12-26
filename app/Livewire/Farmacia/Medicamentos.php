<?php

namespace App\Livewire\Farmacia;

use App\Models\Medicamento;
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
    public function render()
    {
        $medicamentos = Medicamento::select('*')
                ->where(function ($query) {
                    return $query
                        ->orwhere('nombre', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('presentacion', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('concentracion', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('marca', 'LIKE', '%' . $this->search . '%');
                })->paginate($this->show);
        return view('livewire.farmacia.medicamentos', compact('medicamentos'));
    }

    public function agregar(){
       

        $messages = [
            'nombre.required' =>  "Ingresar nombre de medicamento"
        ];

        $rules = [
            'nombre' => 'required',

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

}


