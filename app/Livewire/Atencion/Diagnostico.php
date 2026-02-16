<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\AtencionDiagnostico;
use App\Models\Cie10;
use Livewire\Component;

class Diagnostico extends Component
{

    public $atencion, $id_atencion;
    public $buscar = '';
    public $resultados = [];
    public $tipo = 'SECUNDARIO';

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $this->atencion = Atencion::find($id_atencion);
    }

    protected $listeners = ['refreshDiagnosticos' => '$refresh'];



    public function updatedBuscar()
    {
        if (strlen($this->buscar) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = Cie10::where('codigo', 'like', "%{$this->buscar}%")
            ->orWhere('descripcion', 'like', "%{$this->buscar}%")
            ->limit(8)
            ->get();
    }

    public function seleccionar($id_cie10)
    {
        if (!$this->atencion) return;

        // Evitar duplicados
        $existe = AtencionDiagnostico::where('id_atencion', $this->atencion->id_atencion)
            ->where('id_cie10', $id_cie10)
            ->exists();

        if ($existe) return;

        // Si es principal, bajar los otros
        if ($this->tipo === 'PRINCIPAL') {
            AtencionDiagnostico::where('id_atencion', $this->atencion->id_atencion)
                ->where('tipo', 'PRINCIPAL')
                ->update(['tipo' => 'SECUNDARIO']);
        }

        AtencionDiagnostico::create([
            'id_atencion' => $this->atencion->id_atencion,
            'id_cie10' => $id_cie10,
            'tipo' => $this->tipo
        ]);

        $this->reset(['buscar', 'resultados']);
        $this->dispatch('refreshDiagnosticos');
    }

    public function eliminar($id)
    {
        AtencionDiagnostico::find($id)?->delete();
      
    }
    public function render()
    {
        return view('livewire.atencion.diagnostico', [
            'diagnosticos' => $this->atencion
                ->diagnosticos()
                ->with('cie10')
                ->get()
        ]);
    }
}
