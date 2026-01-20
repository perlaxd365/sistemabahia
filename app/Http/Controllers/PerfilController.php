<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    //
    public function index()
    {
        $carbon = new Carbon();
        return view('admin.perfil.index', compact('carbon'));
    }
}
