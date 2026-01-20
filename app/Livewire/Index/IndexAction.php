<?php

namespace App\Livewire\Index;

use App\Models\Atencion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class IndexAction extends Component
{

    public $search = '';

    use  WithPagination, WithoutUrlPagination;
    protected $paginationTheme = "bootstrap";
    protected $listeners = [
        'atencionActualizada' => '$refresh'
    ];
    public $show;
    public function mount()
    {

        $this->show = 8;
    }

    public function render()
    {


        $atenciones = Atencion::with(['paciente', 'medico', 'comprobante'])
            ->where('estado', 'PROCESO')
            ->when(Auth::user()->nombre_cargo === 'Doctor', function ($query) {
                $query->where('id_medico', Auth::id());
            })
            ->whereHas('paciente', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('dni', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->show);

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
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

}
