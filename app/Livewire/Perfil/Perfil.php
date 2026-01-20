<?php

namespace App\Livewire\Perfil;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Perfil extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.perfil.perfil');
    }
}
