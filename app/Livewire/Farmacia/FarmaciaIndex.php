<?php

namespace App\Livewire\Farmacia;

use Livewire\Component;

class FarmaciaIndex extends Component
{
    public $tab = 'medicamentos';

    protected $queryString = ['tab'];

    public function mount()
    {
        $this->tab = request()->query('tab', 'medicamentos');
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
