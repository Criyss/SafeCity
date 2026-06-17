<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class MapaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('maps.mapa', compact('categorias'));
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