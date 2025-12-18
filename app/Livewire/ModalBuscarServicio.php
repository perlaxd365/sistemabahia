<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ModalBuscarServicio extends Component
{
    public $buscar = '';
    public $resultados = [];
    public $servicioSeleccionado = null; // 👈 IMPORTANTE

    public function updatedBuscar()
    {
        if (strlen($this->buscar) < 1) {
            $this->resultados = [];
            return;
        }

        $this->resultados = DB::table('servicios as s')
            ->join('sub_tipo_servicios as sts', 's.id_subtipo_servicio', '=', 'sts.id_subtipo_servicio')
            ->join('tipo_servicios as ts', 'sts.id_tipo_servicio', '=', 'ts.id_tipo_servicio')
            ->where(function ($query) {
                return $query
                    ->orwhere('s.nombre_servicio', 'LIKE', '%' . $this->buscar . '%')
                    ->orwhere('sts.nombre_subtipo_servicio', 'LIKE', '%' . $this->buscar . '%')
                    ->orWhere('ts.nombre_tipo_servicio', 'LIKE', '%' . $this->buscar . '%');
            })

            ->select(
                's.id_servicio',
                's.nombre_servicio',
                'sts.nombre_subtipo_servicio',
                'ts.nombre_tipo_servicio',
                's.precio_servicio',
            )
            ->orderBy('s.nombre_servicio')
            ->limit(30)
            ->get();
            
    }


    public function seleccionar($id)
    {
        $this->servicioSeleccionado = DB::table('servicios as s')
            ->join('sub_tipo_servicios as sts', 's.id_subtipo_servicio', '=', 'sts.id_subtipo_servicio')
            ->join('tipo_servicios as ts', 'sts.id_tipo_servicio', '=', 'ts.id_tipo_servicio')
            ->where('s.id_servicio', $id)
            ->select(
                's.id_servicio',
                's.nombre_servicio',
                'sts.nombre_subtipo_servicio',
                'ts.nombre_tipo_servicio',
                's.precio_servicio',
            )
            ->first();

             $servicio = collect($this->resultados)
            ->firstWhere('id_servicio', $id);

        $this->dispatch('servicioSeleccionado', $servicio);

        

        // limpiar buscador
        $this->buscar="";
        $this->reset(['buscar', 'resultados']);
    }

    public function render()
    {
        return view('livewire.modal-buscar-servicio');
    }
}
