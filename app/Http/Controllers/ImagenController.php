<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImagenController extends Controller
{
     //
    public function ordenes()
    {
        $carbon = new Carbon();
        return view('admin.imagen.ordenes', compact('carbon'));
    }

    //
    public function resultados($id_orden)
    {
        return view(
            'admin.imagen.resultados',['id_orden' => $id_orden] );
    }
}
