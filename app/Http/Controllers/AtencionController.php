<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AtencionController extends Controller
{
   // 
    public function index()
    {
        $carbon = new Carbon();
        return view('admin.atencion.index', compact('carbon'));
    }

    // 
    public function home($id_atencion)
    {
        return view('admin.atencion.home', ['id_atencion' => $id_atencion]);
    }
}
