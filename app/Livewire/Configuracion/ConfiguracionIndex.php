<?php

namespace App\Livewire\Configuracion;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ConfiguracionIndex extends Component
{
    use  WithPagination, WithoutUrlPagination;
    public $nombre_tipo_servicio, $id_tipo_servicio, $id_subtipo_servicio, $nombre_subtipo_servicio, $nombre_servicio, $precio_servicio;
    protected $paginationTheme = "bootstrap";
    public $show;

    public function mount()
    {
        $this->show = 100;
    }
    public function render()
    {
        $lista_tipos_servicios = TipoServicio::select('*')
            ->where("estado_tipo_servicio", true)->paginate($this->show);
        $lista_sub_tipos_servicios = SubTipoServicio::select('*')
            ->join('tipo_servicios', 'tipo_servicios.id_tipo_servicio', 'sub_tipo_servicios.id_tipo_servicio')
            ->where("estado_subtipo_servicio", true)->paginate($this->show);
        $lista_servicios = Servicio::select('*')
            ->join('sub_tipo_servicios', 'sub_tipo_servicios.id_subtipo_servicio', 'servicios.id_subtipo_servicio')
            ->join('tipo_servicios', 'tipo_servicios.id_tipo_servicio', 'sub_tipo_servicios.id_tipo_servicio')
            ->where("estado_servicio", true)->paginate($this->show);
        return view('livewire.configuracion.configuracion-index', compact('lista_tipos_servicios', 'lista_sub_tipos_servicios', 'lista_servicios'));
    }

    public function agregar_tipo_servicio()
    {

        $messages = [
            'nombre_tipo_servicio.required' => 'Por favor ingresa el nombre del tipo',
        ];

        $rules = [
            'nombre_tipo_servicio' => 'required',

        ];

        $this->validate($rules, $messages);


        TipoServicio::create([
            'nombre_tipo_servicio' =>  $this->nombre_tipo_servicio,
            'estado_tipo_servicio' => true
        ]);
        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registro el tipo de servicio', 'message' => 'Exito']
        );
        $this->default();
    }

    public function default()
    {
        $this->nombre_tipo_servicio = "";
        $this->nombre_subtipo_servicio = "";
        $this->nombre_servicio = "";
        $this->precio_servicio = "";
        $this->id_tipo_servicio = "";
        $this->id_subtipo_servicio = "";
    }

    public function delete_tipo_servicio($id_tipo_servicio)
    {
        $tipo_servicio = TipoServicio::find($id_tipo_servicio);
        $tipo_servicio->update([
            'estado_tipo_servicio' => false
        ]);
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'info', 'title' => 'Se elimino ' . $tipo_servicio->nombre_tipo_servicio, 'message' => 'Exito']
        );
    }

    public function agregar_subtipo_servicio()
    {

        $messages = [
            'id_tipo_servicio.required' => 'Por favor selecciona el tipo de servicio',
            'nombre_subtipo_servicio.required' => 'Por favor ingresa el nombre del sub tipo',
        ];

        $rules = [
            'id_tipo_servicio' => 'required',
            'nombre_subtipo_servicio' => 'required',

        ];

        $this->validate($rules, $messages);


        SubTipoServicio::create([
            'id_tipo_servicio' =>  $this->id_tipo_servicio,
            'nombre_subtipo_servicio' =>  $this->nombre_subtipo_servicio,
            'estado_subtipo_servicio' => true
        ]);
        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registro el Sub tipo de servicio', 'message' => 'Exito']
        );
        $this->default();
    }

    public function delete_subtipo_servicio($id_subtipo_servicio)
    {
        $sub_tipo_servicio = SubTipoServicio::find($id_subtipo_servicio);
        $sub_tipo_servicio->update([
            'estado_subtipo_servicio' => false
        ]);
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'info', 'title' => 'Se elimino ' . $sub_tipo_servicio->nombre_subtipo_servicio, 'message' => 'Exito']
        );
    }


    public function agregar_servicio()
    {

        $messages = [
            'id_subtipo_servicio.required' => 'Por favor selecciona el sub tipo de servicio',
            'nombre_servicio.required' => 'Por favor ingresa el nombre del servicio',
            'precio_servicio.required' => 'Por favor ingresa el precio del servicio',
        ];

        $rules = [
            'id_subtipo_servicio' => 'required',
            'nombre_servicio' => 'required',
            'precio_servicio' => 'required',

        ];

        $this->validate($rules, $messages);


        Servicio::create([
            'id_subtipo_servicio' =>  $this->id_subtipo_servicio,
            'nombre_servicio' =>  $this->nombre_servicio,
            'precio_servicio' =>  $this->precio_servicio,
            'estado_servicio' => true
        ]);
        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registro el servicio correctamente', 'message' => 'Exito']
        );
        $this->default();
    }

    public function delete_servicio($id_servicio)
    {
        $servicio = Servicio::find($id_servicio);
        $servicio->update([
            'estado_servicio' => false
        ]);
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'info', 'title' => 'Se elimino ' . $servicio->nombre_servicio, 'message' => 'Exito']
        );
    }
}
