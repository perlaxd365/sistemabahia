<?php

namespace App\Livewire\Cita;

use App\Models\Cita as ModelsCita;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use UserUtil;

class Cita extends Component
{
    public $fecha;
    public $hora;
    public $id_paciente;
    public $id_medico;
    public $motivo;

    public $pacientes = [];
    public $medicos = [];
    public $citas = [];

    protected $rules = [
        'fecha' => 'required|date',
        'hora' => 'required',
        'id_paciente' => 'required',
        'motivo' => 'nullable|string'
    ];

    public function mount()
    {
        $this->fecha = now()->toDateString();

        $this->pacientes = User::where('privilegio_cargo', 7)
            ->where('estado_user', 1)
            ->orderBy('name')
            ->get();

        $this->medicos = User::where('privilegio_cargo', 2)
            ->where('estado_user', 1)
            ->orderBy('name')
            ->get();

        $this->loadCitas();
    }

    public function loadCitas()
    {
        $this->citas = ModelsCita::with(['paciente', 'medico'])
            ->get()
            ->map(function ($cita) {

                return [
                    'id' => $cita->id_cita,

                    'title' => $cita->paciente->nombre_completo,

                    'start' => $cita->fecha_cita . 'T' . $cita->hora_cita,

                    'extendedProps' => [
                        'paciente' => $cita->paciente->nombre_completo,
                        'medico' => UserUtil::getUserByID($cita->medico)->name ?? '',
                        'motivo' => $cita->motivo
                    ],

                    'color' => match ($cita->estado) {
                        'PROGRAMADA' => '#2563eb',
                        'CONFIRMADA' => '#16a34a',
                        'ATENDIDA' => '#6b7280',
                        'CANCELADA' => '#dc2626',
                        'NO_ASISTIO' => '#f59e0b',
                    }

                ];
            })->toArray();

        $this->dispatch('refrescarCalendario', citas: $this->citas);
    }

    public function guardarCita()
    {
        $this->validate();

        ModelsCita::create([
            'fecha_cita' => $this->fecha,
            'hora_cita' => $this->hora,
            'id_paciente' => $this->id_paciente,
            'medico' => $this->id_medico,
            'motivo' => $this->motivo,
            'estado' => 'PROGRAMADA'
        ]);

        $this->reset(['hora', 'id_paciente', 'id_medico', 'motivo']);

        $this->loadCitas();
        $this->dispatch('recargarPagina');
    }

    #[On('eliminarCita')]
    public function eliminarCita($id)
    {
        $cita = ModelsCita::find($id);

        if ($cita) {
            $cita->delete();
        }

        $this->loadCitas();
        $this->dispatch('recargarPagina');
    }

    public function render()
    {
        return view('livewire.cita.cita');
    }
}
