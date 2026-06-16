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
        $reportes = \App\Models\Reporte::with('categoria')
            ->select('id', 'titulo', 'latitud', 'longitud', 'categoria_id', 'estado')
            ->get()
            ->map(function($r) {
                return [
                    'lat'       => $r->latitud,
                    'lng'       => $r->longitud,
                    'titulo'    => $r->titulo,
                    'categoria' => $r->categoria ? $r->categoria->nombre : 'Sin categoría',
                    'estado'    => $r->estado,
                ];
            });

        return response()->json($reportes);
    }

    public function mapaPublico()
    {
        return view('maps.mapa-publico');
    }

    public function reportesPublicoJson()
    {
        $reportes = \App\Models\Reporte::with('categoria')
            ->select('id', 'titulo', 'latitud', 'longitud', 'categoria_id', 'estado')
            ->get()
            ->map(function($r) {
                return [
                    'lat'    => $r->latitud,
                    'lng'    => $r->longitud,
                    'titulo' => $r->titulo,
                    'tipo'   => $r->categoria ? $r->categoria->nombre : 'Sin categoría',
                    'estado' => $r->estado,
                ];
            });

        return response()->json($reportes);
    }
}