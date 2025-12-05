<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Servicio;
use App\Models\User;
use Livewire\Component;

class AtencionIndex extends Component
{
    public $id_paciente, $id_servicio, $motivo, $diagnostico;
    public $show;
    public function mount()
    {
        $this->show = 20;
    }
    public function render()
    {
        $pacientes = User::where('estado_user', true)->where('privilegio_cargo', 6)->get();
         $servicios = Servicio::select('*')
            ->join('sub_tipo_servicios', 'sub_tipo_servicios.id_subtipo_servicio', 'servicios.id_subtipo_servicio')
            ->join('tipo_servicios', 'tipo_servicios.id_tipo_servicio', 'sub_tipo_servicios.id_tipo_servicio')
            ->where("estado_servicio", true)->paginate($this->show);
        return view('livewire.atencion.atencion-index', compact('pacientes', 'servicios'));
    }

    
    public $step = 1;

    public $notas;


    public function nextStep()
    {
        $this->validateStep();
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function validateStep()
    {
        if ($this->step == 1) {
            $this->validate([
                'id_paciente' => 'required',
            ]);
        }

        if ($this->step == 2) {
            $this->validate([
                'id_servicio' => 'required',
            ]);
        }

        if ($this->step == 3) {
            $this->validate([
                'motivo' => 'required',
            ]);
        }
    }

    public function guardar()
    {
        $this->validate([
            'paciente_id' => 'required',
            'servicio_id' => 'required',
            'motivo' => 'required',
        ]);

        Atencion::create([
            'paciente_id' => $this->paciente_id,
            'servicio_id' => $this->servicio_id,
            'motivo' => $this->motivo,
            'diagnostico' => $this->diagnostico,
            'notas' => $this->notas,
        ]);

        session()->flash('msg', 'Atención registrada correctamente');
        return redirect()->to('/atenciones');
    }
}
