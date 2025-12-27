<?php

namespace App\Livewire\Farmacia;

use App\Models\Compra;
use App\Models\Medicamento;
use App\Models\Proveedor;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
         return view('livewire.farmacia.dashboard', [
            'totalMedicamentos' => Medicamento::count(),
            'stockBajo' => Medicamento::where('stock', '<=', 10)->count(),
            'comprasHoy' => Compra::whereDate('fecha_compra', today())->count(),
            'totalProveedores' => Proveedor::count(),
            /* 'totalProveedores' => VentasHoy::count() */
            
        ]);
    }
}
