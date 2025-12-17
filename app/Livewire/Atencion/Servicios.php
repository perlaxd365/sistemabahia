<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\AtencionServicio;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Servicios extends Component
{
    public $id_atencion;
    public $id_servicio, $id_profesional, $cantidad, $precio_unitario;

    public $buscar = '';
    public $servicioSeleccionado = null;
    public $nombre_paciente = '';

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $atencion = Atencion::find($id_atencion);
        $paciente = User::find($atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
    }


    public function render()
    {
        $servicios = Servicio::all();
        $profesionales = User::where(function ($query) {
                return $query
                    ->orwhere('privilegio_cargo', 2)
                    ->orwhere('privilegio_cargo', 3)
                    ->orwhere('privilegio_cargo', 4)
                    ->orwhere('privilegio_cargo', 6);
            })
            ->orderBy('name')
            ->get();
        $atencion_servicios = AtencionServicio::where('atencion_servicios.id_atencion', $this->id_atencion)
        ->join('atencions','atencions.id_atencion','atencions.id_atencion')
        ->join('servicios','servicios.id_servicio','atencion_servicios.id_servicio')
        ->join('users','users.id','atencion_servicios.id_profesional')
        ->get();
        return view('livewire.atencion.servicios', compact('servicios', 'profesionales', 'atencion_servicios'));
    }

    protected $listeners = ['servicioSeleccionado'];


    public function servicioSeleccionado($servicio)
    {
        $this->servicioSeleccionado = $servicio;
        $this->id_servicio = $servicio["id_servicio"];
        $this->precio_unitario = $servicio["precio_servicio"];
        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se agregó ' . $servicio["nombre_servicio"], ' correctamente', 'message' => 'Exito']
        );
    }
    public function agregarServicio()
    {

        $messages = [
            'id_servicio.required' => 'Por favor seleccionar el servicio',
            'precio_unitario.required' => 'Por favor ingresar la cantidad de servicios',
            'cantidad.required' => 'Por favor ingresar el precio unitario por servicio',

        ];

        $rules = [
            'id_servicio' => 'required',
            'precio_unitario' => 'required',
            'cantidad' => 'required',

        ];
        $this->validate($rules, $messages);


        AtencionServicio::create([
            'id_servicio' => $this->id_servicio,
            'id_atencion' => $this->id_atencion,
            'id_profesional' => $this->id_profesional,
            'id_responsable' => auth()->user()->id,
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precio_unitario,
            'subtotal' => ($this->precio_unitario * $this->cantidad),
            'estado' => true,
        ]);

        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se agrego el servicio a la atención de '. $this->nombre_paciente, 'message' => 'Exito']
        );

        $this->default();
    }

    public function default(){
        $this->servicioSeleccionado = [];
        $this->id_profesional = '';
        $this->cantidad = '';
        $this->precio_unitario = '';
    }
}
