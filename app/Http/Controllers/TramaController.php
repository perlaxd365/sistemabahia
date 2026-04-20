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


    //tab1
    public function trama_tab1()
    {
        $carbon = new Carbon();
        return view('admin.trama.trama_tab1', compact('carbon'));
    }

    //tab2
    public function trama_tab2()
    {
        $carbon = new Carbon();
        return view('admin.trama.trama_tab2', compact('carbon'));
    }

    //tac1
    public function trama_tac1()
    {
        $carbon = new Carbon();
        return view('admin.trama.trama_tac1', compact('carbon'));
    }

    //tac1
    public function trama_tac2()
    {
        $carbon = new Carbon();
        return view('admin.trama.trama_tac2', compact('carbon'));
    }

    //tad1
    public function trama_tad1()
    {
        $carbon = new Carbon();
        return view('admin.trama.trama_tad1', compact('carbon'));
    }

    //tad2
    public function trama_tad2()
    {
        $carbon = new Carbon();
        return view('admin.trama.trama_tad2', compact('carbon'));
    }
}
