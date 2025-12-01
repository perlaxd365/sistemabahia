<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
     // //
    public function index()
    {
        $carbon = new Carbon();
        return view('admin.configuracion.index', compact('carbon'));
    }

}
