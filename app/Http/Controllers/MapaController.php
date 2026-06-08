<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapaController extends Controller
{
    public function index()
    {
        return view('maps.mapa');
    }

    public function reportesJson()
    {
        return response()->json([]);
    }
}