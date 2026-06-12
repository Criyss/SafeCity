<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reporte;
use App\Models\Categoria;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsuarios  = User::count();
        $totalReportes  = Reporte::count();
        $resueltos      = 0;
        $pendientes     = 0;

        // Últimos 10 reportes
        $ultimosReportes = Reporte::with('categoria')
            ->latest()
            ->take(10)
            ->get();

        // Datos para gráfica de pastel por categoría
        $categorias = Categoria::withCount('reportes')->get();
        $categoriaLabels = $categorias->pluck('nombre')->toArray();
        $categoriaData   = $categorias->pluck('reportes_count')->toArray();

        // Datos para gráfica de barras por departamento
        $departamentos = ['La Paz','Cochabamba','Santa Cruz','Oruro','Potosí','Chuquisaca','Tarija','Beni','Pando'];
        $departamentoData = [];
        foreach ($departamentos as $dep) {
            $departamentoData[] = 0;
        }

        // Datos para gráfica de líneas por mes
        $mensualData = array_fill(0, 12, 0);

        return view('dashboard.index', compact(
            'totalUsuarios',
            'totalReportes',
            'resueltos',
            'pendientes',
            'ultimosReportes',
            'categoriaLabels',
            'categoriaData',
            'departamentoData',
            'mensualData'
        ));
    }
}