<?php

namespace App\Livewire\Atencion;

use Livewire\Component;

class Home extends Component
{

    public $id_atencion;
    public $tab = 'info';  // tab activa
    public $componente = 'atencion.InfoPaciente'; // componente activo
    public $privilegio;
    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $this->privilegio = auth()->user()->privilegio_cargo;
    }
    public function puedeVer(array $rolesPermitidos): bool
    {
        return in_array($this->privilegio, $rolesPermitidos);
    }
    public function cambiarTab($tab)
    {


        $permisosPorTab = [
            'info'         => [1, 2, 3, 4, 5, 6],
            'medico'       => [1, 5],
            'servicios'    => [1, 5],
            'signos'       => [1, 2, 3, 5],
            'consulta'     => [1, 2, 3, 5],
            'medicamentos' => [1, 5, 6],
            'laboratorio'  => [1, 2, 5],
            'imagen'       => [1, 2, 5],
            'insumos'      => [1, 2],
            'resultados'   => [1, 2, 5],
            'facturacion'  => [1, 5],
        ];

        if (
            ! isset($permisosPorTab[$tab]) ||
            ! in_array($this->privilegio, $permisosPorTab[$tab])
        ) {
            abort(403, 'No tiene permisos para esta sección');
        }

        $this->tab = $tab;

        $map = [
            'info'          => 'atencion.info-paciente',
            'medico'        => 'atencion.medico',
            'servicios'     => 'atencion.servicios',
            'signos'        => 'atencion.signos-vitales',
            'consulta'      => 'atencion.consulta',
            'medicamentos'  => 'atencion.medicamentos',
            'laboratorio'   => 'atencion.laboratorio',
            'imagen'        => 'atencion.imagen',
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
