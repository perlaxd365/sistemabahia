<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Servicio;
use App\Models\User;
use DateUtil;
use Livewire\Attributes\On;
use Livewire\Component;

class AtencionIndex extends Component
{
    public $id_paciente, $id_servicio, $motivo, $diagnostico;
    public $show;
    public $dni, $name, $fecha_nacimiento, $telefono;
    public $tipo_atencion;
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
        $atenciones = Atencion::all();
        return view('livewire.atencion.atencion-index', compact('pacientes', 'servicios', 'atenciones'));
    }


    public $step = 1;

    public $notas;

    public function default()
    {
        $this->id_paciente = "";
        $this->dni = "";
        $this->name = "";
        $this->telefono = "";
        $this->fecha_nacimiento = "";
    }

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
                'dni' => 'required',
                'name' => 'required',
                'telefono' => 'required',
                'fecha_nacimiento' => 'required',
            ]);
        }

        if ($this->step == 2) {
            $this->dispatch('init-ckeditor');
        }

        if ($this->step == 3) {
            $this->dispatch('get-ckeditor');

            $this->validate([
                'tipo_atencion' => 'required',
            ]);
            dd($this->tipo_atencion);
        }
    }

    public function buscarPaciente()
    {

        if ($this->dni) {
            # code...
            $paciente = User::where('dni', $this->dni)->where('privilegio_cargo', 7)->first();
            if ($paciente) {
                # code...
                $this->id_paciente = $paciente->id;
                $this->name = $paciente->name;
                $this->telefono = $paciente->telefono;
                $this->fecha_nacimiento = DateUtil::getFechaSimple($paciente->fecha_estudiante);
                $this->dispatch('paciente-encontrado');
                $this->resetErrorBag();
                $this->resetValidation();
            } else {
                $this->default();
                $this->dispatch('paciente-no-existe');
            }
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

    
    #[On('editorUpdated')]
    public function updateEditorValue($value)
    {
        $this->tipo_atencion = $value;

        dd($this->tipo_atencion);
        logger("Livewire recibió el valor: " . $value);
    }
}
