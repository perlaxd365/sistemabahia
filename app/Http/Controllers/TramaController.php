<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TramaController extends Controller
{
    //
    public function index()
    {
        $carbon = new Carbon();
        return view('admin.trama.index', compact('carbon'));
    }
    //
    public function trama_aga()
    {
        $carbon = new Carbon();
        return view('admin.trama.trama_aga', compact('carbon'));
    }
}
