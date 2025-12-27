<?php

namespace App\Livewire\Farmacia;

use Livewire\Component;

class FarmaciaIndex extends Component
{
    public $tab = 'dashboard';

    protected $queryString = ['tab'];

    public function mount()
    {
        $this->tab = request()->query('tab', 'dashboard');
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.farmacia.farmacia-index');
    }
}
