<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Categoria;
use App\Models\EstadoReporte;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol === 'ciudadano') {
            $reportes = Reporte::with(['categoria'])
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        } else {
            $reportes = Reporte::with(['categoria', 'user'])
                ->latest()
                ->paginate(10);
        }
        return view('reportes.index', compact('reportes'));
    }

    public function show(Reporte $reporte)
    {
        $estados = $reporte->estados()->with('user')->latest()->get();
        return view('reportes.show', compact('reporte', 'estados'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('reportes.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'        => 'required|string|max:255',
            'categoria_id'  => 'required|exists:categorias,id',
            'descripcion'   => 'required|string',
            'departamento'  => 'required|in:La Paz,Cochabamba,Santa Cruz,Oruro,Potosí,Chuquisaca,Tarija,Beni,Pando',
            'gravedad'      => 'required|in:Baja,Media,Alta,Crítica',
            'latitud'       => 'required|numeric',
            'longitud'      => 'required|numeric',
            'foto'          => 'nullable|image|max:10240',
        ]);

        $base64String = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $imageContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getClientMimeType();
            $base64String = 'data:'.$mimeType.';base64,'.base64_encode($imageContent);
        }

        $reporte = Reporte::create([
            'user_id'      => Auth::id(),
            'categoria_id' => $request->categoria_id,
            'titulo'       => $request->titulo,
            'descripcion'  => $request->descripcion,
            'departamento' => $request->departamento,
            'gravedad'     => $request->gravedad,
            'latitud'      => $request->latitud,
            'longitud'     => $request->longitud,
            'foto_base64'  => $base64String,
            'estado'       => 'Pendiente',
        ]);

        // Registrar estado inicial en el historial
        EstadoReporte::create([
            'reporte_id'      => $reporte->id,
            'user_id'         => Auth::id(),
            'estado_anterior' => 'Nuevo',
            'estado_nuevo'    => 'Pendiente',
            'comentario'      => 'Reporte creado.',
        ]);

        return redirect('/reportes')->with('success', 'Reporte enviado correctamente.');
    }

    public function cambiarEstado(Request $request, Reporte $reporte)
    {
        $request->validate([
            'estado_nuevo' => 'required|in:Pendiente,En revisión,En proceso,Resuelto,Cerrado',
            'comentario'   => 'nullable|string',
        ]);

        // Guarda el cambio de estado en el historial
        EstadoReporte::create([
            'reporte_id'      => $reporte->id,
            'user_id'         => Auth::id(),
            'estado_anterior' => $reporte->estado,
            'estado_nuevo'    => $request->estado_nuevo,
            'comentario'      => $request->comentario,
        ]);

        $reporte->update(['estado' => $request->estado_nuevo]);

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }
}
