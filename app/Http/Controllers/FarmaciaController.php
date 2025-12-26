<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FarmaciaController extends Controller
{
    //
    public function index()
    {
        $carbon = new Carbon();
        return view('admin.farmacia.index', compact('carbon'));
    }
}
