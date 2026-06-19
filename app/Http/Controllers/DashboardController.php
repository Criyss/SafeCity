<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reporte;
use App\Models\Categoria;

// Panel estadístico — Hecho por Keyra
class DashboardController extends Controller
{
    public function index()
    {
        // Contadores KPI para las 4 tarjetas
        $totalUsuarios = User::count();
        $totalReportes = Reporte::count();
        $resueltos     = Reporte::where('estado', 'Resuelto')->count();
        $pendientes    = Reporte::where('estado', 'Pendiente')->count();

        // Últimos 10 reportes para la tabla
        $ultimosReportes = Reporte::with(['categoria', 'user'])->latest()->take(10)->get();

        // Datos para la gráfica de pastel (por categoría)
        $categorias      = Categoria::withCount('reportes')->get();
        $categoriaLabels = $categorias->pluck('nombre')->toArray();
        $categoriaData   = $categorias->pluck('reportes_count')->toArray();

        // Datos para la gráfica de barras (por departamento)
        $departamentoLabels = ['La Paz', 'Cochabamba', 'Santa Cruz', 'Oruro', 'Potosí', 'Chuquisaca', 'Tarija', 'Beni', 'Pando'];
        $departamentoData   = [];
        foreach ($departamentoLabels as $dep) {
            $departamentoData[] = Reporte::where('departamento', $dep)->count();
        }

        // Datos para la gráfica de líneas (reportes por mes del año actual)
        $anio            = now()->year;
        $tendenciaMensual = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $tendenciaMensual[] = Reporte::whereYear('created_at', $anio)
                                         ->whereMonth('created_at', $mes)
                                         ->count();
        }

        return view('dashboard.index', compact(
            'totalUsuarios', 'totalReportes', 'resueltos', 'pendientes',
            'ultimosReportes', 'categoriaLabels', 'categoriaData',
            'departamentoLabels', 'departamentoData', 'tendenciaMensual'
        ));
    }
}
