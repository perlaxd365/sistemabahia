<?php

namespace App\Livewire\Index;

use App\Models\Atencion;
use App\Models\User;
use Livewire\Component;

class IndexAction extends Component
{

    public $search = '';

    protected $listeners = [
        'atencionActualizada' => '$refresh'
    ];

    public function render()
    {

        $atenciones = Atencion::with(['paciente'])
            ->where('estado', 'PROCESO')
            ->where(function ($query) {
                $query->whereHas('paciente', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('dni', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsuarios = User::count();
        $activos       = User::where('estado_user', 1)->count();
        $inactivos     = User::where('estado_user', 0)->count();
        $nuevos        = User::where('created_at', '>=', now()->subDays(30))->count();
        $privilegio    = auth()->user()->privilegio_cargo;

        return view('livewire.index.index-action', compact(
            'totalUsuarios',
            'activos',
            'inactivos',
            'nuevos',
            'privilegio',
            'atenciones'
        ));
    }
}
