<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Servicio;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateUtil;
use Livewire\Attributes\On;
use Livewire\Component;
use PacienteUtil;
use Symfony\Component\HttpFoundation\Request;

class AtencionIndex extends Component
{
    public $id_paciente, $id_servicio, $motivo, $diagnostico;
    public $show;
    public $dni, $name, $fecha_nacimiento, $telefono;
    public $tipo_atencion;
    public function mount(Request $request)
    {
        if ($request->dni) {
            $this->dni = $request->dni;
            $this->step = 2;
            $paciente = User::where('dni', $this->dni)->where('privilegio_cargo', 7)->first();
            $this->id_paciente = $paciente->id;
            $this->dni = $paciente->dni;
            $this->name = $paciente->name;
            $this->telefono = $paciente->telefono;
            $this->fecha_nacimiento = $paciente->fecha_nacimiento;
        }
        $this->show = 20;
    }
    public function render()
    {
        $pacientes = User::where('estado_user', true)->where('privilegio_cargo', 6)->get();
        $servicios = Servicio::select('*')
            ->join('sub_tipo_servicios', 'sub_tipo_servicios.id_subtipo_servicio', 'servicios.id_subtipo_servicio')
            ->join('tipo_servicios', 'tipo_servicios.id_tipo_servicio', 'sub_tipo_servicios.id_tipo_servicio')
            ->where("estado_servicio", true)->paginate($this->show);
        if ($this->id_paciente) {
            $id_historia = PacienteUtil::getHistoria($this->id_paciente);
            $atenciones = Atencion::where('id_historia', $id_historia)->orderby('id_atencion', 'desc')->get();
        } else {
            $atenciones = [];
        }

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
        $this->tipo_atencion = "";
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
            $this->guardar();
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
            'id_paciente' => 'required',
            'tipo_atencion' => 'required',
        ]);

        $id_historia = PacienteUtil::addHistoria($this->id_paciente);
        Atencion::create([
            'id_paciente' => $this->id_paciente,
            'id_responsable' => auth()->user()->id,
            'id_historia' => $id_historia,
            'tipo_atencion' => $this->tipo_atencion,
            'fecha_inicio_atencion' => now(),
            'estado_atencion' => true,
        ]);

        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registro la atencion de ' . $this->name . ' correctamente', 'message' => 'Exito']
        );
        // ✅ Redireccionar a a tencion
        $user = User::find($this->id_paciente);
        $this->default();
        return redirect()->route('atencion', [
            'dni' => $user->dni
        ]);
    }


    #[On('editorUpdated')]
    public function updateEditorValue($value)
    {
        $this->tipo_atencion = $value;
    }

    public function exportarPDF()
    {

        $id_historia = PacienteUtil::getHistoria($this->id_paciente);
        $atenciones = Atencion::where('id_historia', $id_historia)->get();
        if (count($atenciones) > 0) {
            $name = $this->name;

            $pdf = Pdf::loadView('reportes.atenciones', compact('atenciones', 'name'));

            return response()->streamDownload(
                fn() => print($pdf->output()),
                'atenciones.pdf'
            );
        } else {

            $this->dispatch(
                'alert',
                ['type' => 'info', 'title' => 'No se encontraron atenciones', 'message' => 'Sin atenciones']
            );
        }
    }
}
