<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ProveedorAction extends Component
{
    public $id_proveedor, $razon_social, $ruc, $telefono, $email, $direccion, $contacto;
    use  WithPagination, WithoutUrlPagination;
    public $search;
    protected $paginationTheme = "bootstrap";
    public $show;

    public function mount()
    {
        $this->show = 20;
    }
    public function render()
    {
        $proveedores = Proveedor::select('*')
                ->where(function ($query) {
                    return $query
                        ->orwhere('razon_social', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('ruc', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('contacto', 'LIKE', '%' . $this->search . '%')
                        ->orwhere('email', 'LIKE', '%' . $this->search . '%');
                })->paginate($this->show);
        return view('livewire.proveedor.proveedor-action', compact('proveedores'));
    }


    public function agregar()
    {

        $messages = [
            'razon_social.required' =>  "Ingresar razón social"
        ];

        $rules = [
            'razon_social' => 'required',

        ];

        $this->validate($rules, $messages);


        Proveedor::create([
            'razon_social' =>  $this->razon_social,
            'ruc' =>  $this->ruc,
            'telefono' =>  $this->telefono,
            'email' =>  $this->email,
            'direccion' =>  $this->direccion,
            'contacto' =>  $this->contacto,
        ]);
        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registro al proveedor correctamente', 'message' => 'Exito']
        );
        $this->default();
    }

    public function default()
    {
        $this->razon_social = "";
        $this->ruc = "";
        $this->telefono = "";
        $this->email = "";
        $this->direccion = "";
        $this->contacto = "";
    }

    public function delete_proveedor($id_proveedor)
    {
        $proveedor = Proveedor::find($id_proveedor);
        $proveedor->update([
            'estado' => false
        ]);
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'info', 'title' => 'Se elimino al proveedor' . $proveedor->razon_social. ' correctamente', 'message' => 'Exito']
        );
    }

    
    public function habilitar_proveedor($id_proveedor)
    {
        $proveedor = Proveedor::find($id_proveedor);
        $proveedor->update([
            'estado' => true
        ]);
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'info', 'title' => 'Se habilitó al proveedor' . $proveedor->razon_social. ' correctamente', 'message' => 'Exito']
        );
    }
}
