<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    //
    public function ordenes()
    {
        $carbon = new Carbon();
        return view('admin.laboratorio.ordenes', compact('carbon'));
    }

    //
    public function resultados($id_orden)
    {
        return view(
            'admin.laboratorio.resultados',['id_orden' => $id_orden] );
    }
}
