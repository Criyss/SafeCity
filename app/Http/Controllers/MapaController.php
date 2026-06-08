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
    public function mapaPublico()
{
    return view('maps.mapa-publico');
}

public function reportesPublicoJson()
{
    return response()->json([
        ['lat' => -16.5, 'lng' => -68.15, 'titulo' => 'Zona de riesgo La Paz', 'tipo' => 'Robos y Asaltos'],
        ['lat' => -17.39, 'lng' => -66.16, 'titulo' => 'Zona insegura Cochabamba', 'tipo' => 'Zonas Inseguras'],
        ['lat' => -17.78, 'lng' => -63.18, 'titulo' => 'Emergencia Santa Cruz', 'tipo' => 'Emergencias'],
        ['lat' => -19.58, 'lng' => -65.75, 'titulo' => 'Zona de riesgo Potosí', 'tipo' => 'Robos y Asaltos'],
        ['lat' => -21.53, 'lng' => -64.73, 'titulo' => 'Zona insegura Tarija', 'tipo' => 'Zonas Inseguras'],
    ]);
}
}