<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Categoria;

// Controlador del mapa — Hecho por Keyra
class MapaController extends Controller
{
    // Carga la vista del mapa interno con las categorías para el filtro
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('maps.mapa', compact('categorias'));
    }

    // Devuelve los reportes en JSON para que el mapa los dibuje como marcadores
    public function reportesJson()
    {
        $reportes = Reporte::with('categoria')
            ->select('id', 'titulo', 'latitud', 'longitud', 'categoria_id', 'estado', 'departamento', 'gravedad')
            ->get()
            ->map(function ($r) {
                return [
                    'lat'          => $r->latitud,
                    'lng'          => $r->longitud,
                    'titulo'       => $r->titulo,
                    'categoria'    => $r->categoria ? $r->categoria->nombre : 'Sin categoría',
                    'estado'       => $r->estado,
                    'departamento' => $r->departamento,
                    'gravedad'     => $r->gravedad,
                ];
            });

        return response()->json($reportes);
    }

    // Carga el mapa público (sin login) para turistas
    public function mapaPublico()
    {
        return view('maps.mapa-publico');
    }

    // Devuelve reportes en JSON para el mapa público
    public function reportesPublicoJson()
    {
        $reportes = Reporte::with('categoria')
            ->select('id', 'titulo', 'latitud', 'longitud', 'categoria_id', 'estado', 'departamento', 'gravedad')
            ->get()
            ->map(function ($r) {
                return [
                    'lat'          => $r->latitud,
                    'lng'          => $r->longitud,
                    'titulo'       => $r->titulo,
                    'tipo'         => $r->categoria ? $r->categoria->nombre : 'Sin categoría',
                    'estado'       => $r->estado,
                    'departamento' => $r->departamento,
                    'gravedad'     => $r->gravedad,
                ];
            });

        return response()->json($reportes);
    }
}
