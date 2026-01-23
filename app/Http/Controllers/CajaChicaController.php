<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CajaChicaController extends Controller
{
    public function index()
    {
        $carbon = new Carbon();
        return view('admin.cajachica.index', compact('carbon'));
    }
}
