<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use Carbon\Carbon;
use Livewire\Component;

class Facturacion extends Component
{

    public Atencion $atencion;
    public ?Comprobante $comprobante = null;

    public function mount($id_atencion)
    {
        $this->atencion = Atencion::with([
            'servicios',
            'medicamentos'
        ])->findOrFail($id_atencion);

        $this->comprobante = Comprobante::where('id_atencion', $id_atencion)
            ->with('detalles')
            ->first();
    }

    public function crearBorrador()
    {
        $this->comprobante = Comprobante::create([
            'tipo_comprobante' => 'BOLETA',
            'serie' => 'B001',
            'fecha_emision' => Carbon::now(),
            'id_atencion' => $this->atencion->id_atencion,
            'id_paciente' => $this->atencion->id_paciente,
            'estado' => 'BORRADOR',
        ]);

        $this->cargarItems();
    }

    protected function cargarItems()
    {
        foreach ($this->atencion->servicios as $servicio) {
            ComprobanteDetalle::create([
                'id_comprobante' => $this->comprobante->id_comprobante,
                'descripcion' => $servicio->nombre,
                'cantidad' => 1,
                'precio_unitario' => $servicio->precio,
                'subtotal' => $servicio->precio,
                'igv' => round($servicio->precio * 0.18, 2),
            ]);
        }

        $this->recalcularTotales();
    }

    protected function recalcularTotales()
    {
        $subtotal = $this->comprobante->detalles->sum('subtotal');
        $igv = $this->comprobante->detalles->sum('igv');

        $this->comprobante->update([
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $subtotal + $igv,
        ]);
    }


    public function render()
    {
        return view('livewire.atencion.facturacion');
    }
}
