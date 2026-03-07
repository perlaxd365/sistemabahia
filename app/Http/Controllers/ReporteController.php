<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
      //
    public function ingreso()
    {
        $carbon = new Carbon();
        return view('admin.reporte.ingreso', compact('carbon'));
    }

     //
    public function venta()
    {
        $carbon = new Carbon();
        return view('admin.reporte.venta', compact('carbon'));
    }
}
