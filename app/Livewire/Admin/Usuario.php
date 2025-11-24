<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Usuario extends Component
{
    use  WithPagination, WithoutUrlPagination;
    protected $paginationTheme = "bootstrap";
    public $search;
    public $view = "create";
    public $show;
    public $table;
     public function mount()
    {
        $this->show = 2;
        $this->table = true;
    }
    public function render()
    {
        return view('livewire.admin.usuario');
    }
}
