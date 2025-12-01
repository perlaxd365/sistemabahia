<?php

namespace App\Livewire\Index;

use App\Models\User;
use Livewire\Component;

class IndexAction extends Component
{
    public function render()
    {
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
            'privilegio'
        ));
    }
}
