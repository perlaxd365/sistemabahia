<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SunatController extends Controller
{
    // 
    public function consultarticket()
    {
        $carbon = new Carbon();
        return view('admin.sunat.consultarticket', compact('carbon'));
    }
}
