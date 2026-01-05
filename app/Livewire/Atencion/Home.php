<?php

namespace App\Livewire\Atencion;

use Livewire\Component;

class Home extends Component
{
    
    public $id_atencion;
    public $tab = 'info';  // tab activa
    public $componente = 'atencion.InfoPaciente'; // componente activo

     public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
    }
     public function cambiarTab($tab)
    {
        $this->tab = $tab;

        $map = [
            'info'          => 'atencion.info-paciente',
            'servicios'     => 'atencion.servicios',
            'signos'        => 'atencion.signos-vitales',
            'consulta'      => 'atencion.consulta',
            'medicamentos'  => 'atencion.medicamentos',
            'laboratorio'   => 'atencion.laboratorio',
            'imagen'         => 'atencion.imagen',
            'resultados'    => 'atencion.resultados',
            'insumos'       => 'atencion.insumos',
            'facturacion'   => 'atencion.facturacion',
        ];

        $this->componente = $map[$tab];
    }

    public function render()
    {
        return view('livewire.atencion.home');
    }
}
