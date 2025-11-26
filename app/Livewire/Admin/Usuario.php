<?php

namespace App\Livewire\Admin;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Usuario extends Component
{
    public $id_usuario, $name, $dni, $direccion, $telefono, $email, $password, $nombre_cargo, $especialidad_cargo, $colegiatura_cargo, $foto_url;
    public $showDetail = false;

    ////// image

    public $file;
    public $url;

    use  WithPagination, WithoutUrlPagination, WithFileUploads;
    protected $paginationTheme = "bootstrap";
    public $search;
    public $view = "create";
    public $show;
    public $table;
    public function mount()
    {
        $this->show = 8;
        $this->table = true;
    }
    public function render()
    {
        $lista_usuarios = User::select('*')
            ->where(function ($query) {
                return $query
                    ->orwhere('name', 'LIKE', '%' . $this->search . '%')
                    ->orwhere('dni', 'LIKE', '%' . $this->search . '%')
                    ->orwhere('email', 'LIKE', '%' . $this->search . '%');
            })->paginate($this->show);

        return view('livewire.admin.usuario',  compact('lista_usuarios'));
    }

    public function showDetailCargo()
    {
        if ($this->nombre_cargo == "Doctor") {
            $this->showDetail = true;
            $this->especialidad_cargo = '';
            $this->colegiatura_cargo = '';
        } else {
            $this->showDetail = false;
            $this->especialidad_cargo = '';
            $this->colegiatura_cargo = '';
        }
    }

    public function agregar()
    {
        $messages = [
            'name.required' => 'Por favor ingresar el nombre del usuario',
            'email.required' => 'Por favor ingresar el correo del usuario',
            'password.required' => 'Por favor ingresar la contraseña del usuario',
            'dni.required' => 'Por favor ingresar el dni del usuario',
            'telefono.required' => 'Por favor ingresar el telefono de contacto del usuario',
            'direccion.required' => 'Por favor ingresar la direccion del usuario',
            'nombre_cargo.required' => 'Por favor ingresar la direccion del usuario',
            'foto_perfil' => 'required|file|max:10240', // 10 MB
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'dni' => 'required|unique:users,dni',
            'telefono' => 'required',
            'direccion' => 'required',
            'nombre_cargo' => 'required',

        ];
        $this->validate($rules, $messages);

        $privilegio = 0;
        switch ($this->nombre_cargo) {
            case 'Administrador':
                $privilegio = 1;
                break;
            case 'Doctor':
                $privilegio = 2;
                break;
            case 'Enfermero':
                $privilegio = 3;
                break;
            case 'Recepcionista':
                $privilegio = 4;
                break;
            case 'Farmaceutico':
                $privilegio = 5;
                break;

            default:
                # code...
                break;
        }



        $path = Storage::disk('cloudinary')->put('foto_perfil', $this->foto_url);

        $url = Storage::disk('cloudinary')->url($path);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'dni' => $this->dni,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'nombre_cargo' => $this->nombre_cargo,
            'especialidad_cargo' => $this->especialidad_cargo,
            'colegiatura_cargo' => $this->colegiatura_cargo,
            'privilegio_cargo' => $privilegio,
            'foto_url' => $url,
            'estado_user' => true,
        ]);

        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registro el usuario correctamente', 'message' => 'Exito']
        );

        $this->default();
    }

    public function default()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->dni = '';
        $this->telefono = '';
        $this->direccion = '';
        $this->nombre_cargo = '';
        $this->especialidad_cargo = '';
        $this->colegiatura_cargo = '';
        $this->foto_url = '';
        $this->table = true;
        $this->dispatch('gotoTop');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function edit($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        $this->view = "edit";
        $user = User::find($id_usuario);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->telefono = $user->telefono;
        $this->dni = $user->dni;
        $this->telefono = $user->telefono;
        $this->direccion = $user->direccion;
        $this->nombre_cargo = $user->nombre_cargo;
        $this->showDetailCargo();
        $this->especialidad_cargo = $user->especialidad_cargo;
        $this->colegiatura_cargo = $user->colegiatura_cargo;
        $this->foto_url = $user->foto_url;

        $this->dispatch('gotoTop');
        $this->resetErrorBag();
        $this->resetValidation();
    }


    public function update()
    {
        $messages = [
            'name.required' => 'Por favor ingresar el nombre del usuario',
            'email.required' => 'Por favor ingresar el correo del usuario',
            'dni.required' => 'Por favor ingresar el dni del usuario',
            'telefono.required' => 'Por favor ingresar el telefono de contacto del usuario',
            'direccion.required' => 'Por favor ingresar la direccion del usuario',
            'nombre_cargo.required' => 'Por favor ingresar la direccion del usuario',
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'dni' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'nombre_cargo' => 'required',

        ];
        $this->validate($rules, $messages);
        $privilegio=0;
         switch ($this->nombre_cargo) {
            case 'Administrador':
                $privilegio = 1;
                break;
            case 'Doctor':
                $privilegio = 2;
                break;
            case 'Enfermero':
                $privilegio = 3;
                break;
            case 'Recepcionista':
                $privilegio = 4;
                break;
            case 'Farmaceutico':
                $privilegio = 5;
                break;

            default:
                # code...
                break;
        }

        $user = User::find($this->id_usuario);
        $user->update([
            'name' => $this->name,
            'dni' => $this->dni,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'nombre_cargo' => $this->nombre_cargo,
            'especialidad_cargo' => $this->especialidad_cargo,
            'colegiatura_cargo' => $this->colegiatura_cargo,
            'privilegio_cargo' => $privilegio,
        ]);

        



        if ($this->password != '') {
            # code...
            $user->update([
                'password' => bcrypt($this->password),
            ]);
        }
        $this->default();
        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se actualizó el usuario correctamente', 'message' => 'Exito']
        );
    }
}
