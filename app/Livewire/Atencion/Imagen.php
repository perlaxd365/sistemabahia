<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\ImagenArea;
use App\Models\ImagenOrden;
use App\Models\ImagenOrdenDetalle;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Imagen extends Component
{

    public $id_atencion;
    public $areas;
    public $estudiosSeleccionados = [];
    public $diagnostico;
    public $nombre_paciente, $fecha_nacimiento;

    //manual
    public $imagenManual = false;
    public $descripcionManual;

    public $ordenesImagen = [];


    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;

        $atencion = Atencion::find($id_atencion);
        $paciente = User::find($atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
        $this->fecha_nacimiento = $paciente->fecha_nacimiento;
        $this->areas = ImagenArea::with('estudios')
            ->orderBy('id_area_imagen')
            ->get();

        //listado 
        $this->ordenesImagen = ImagenOrden::with('detalles.estudio')
            ->where('id_atencion', $id_atencion)
            ->orderBy('created_at', 'desc')
            ->get();
        
        }
    public function guardarOrden()
    {
        if (count($this->estudiosSeleccionados) === 0 && !$this->imagenManual) {
            $this->dispatch(
                'alert',
                ['type' => 'warning', 'title' => 'Seleccione un estudio o ingrese uno manual.', 'message' => 'Atención']
            );
            return;
        }



        DB::transaction(function () {

            $orden = ImagenOrden::create([
                'id_atencion' => $this->id_atencion,
                'fecha' => now(),
                'diagnostico' => $this->diagnostico,
            ]);

            // 📌 Estudios del catálogo
            foreach ($this->estudiosSeleccionados as $id_estudio) {
                
                ImagenOrdenDetalle::create([
                    'id_orden_imagen' => $orden->id_orden_imagen,
                    'id_estudio' => $id_estudio,
                ]);
            }

            // ✍️ Imagen manual
            if ($this->imagenManual && $this->descripcionManual) {
                ImagenOrdenDetalle::create([
                    'id_orden_imagen' => $orden->id_orden_imagen,
                    'descripcion_manual' => $this->descripcionManual,
                ]);
            }
        });

        $this->reset([
            'estudiosSeleccionados',
            'imagenManual',
            'descripcionManual',
            'diagnostico'
        ]);

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Orden de imágenes registrada para ' . $this->nombre_paciente . ' con éxito.', 'message' => 'Exito']
        );
    }
    public function render()
    {
        return view('livewire.atencion.imagen');
    }
}
