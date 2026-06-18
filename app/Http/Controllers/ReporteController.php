<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Categoria;
use App\Models\EstadoReporte;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $buscar   = $request->get('buscar');
        $filtroEst = $request->get('estado');
        $filtroCat = $request->get('categoria_id');

        $query = Reporte::with(['categoria', 'user'])->latest();

        // Ciudadano solo ve sus propios reportes
        if (Auth::user()->rol === 'ciudadano') {
            $query->where('user_id', Auth::id());
        }

        $query->when($buscar, fn($q) => $q->where('titulo', 'LIKE', '%'.$buscar.'%'))
              ->when($filtroEst, fn($q) => $q->where('estado', $filtroEst))
              ->when($filtroCat, fn($q) => $q->where('categoria_id', $filtroCat));

        $reportes  = $query->paginate(10)->withQueryString();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('reportes.index', compact('reportes', 'categorias', 'buscar', 'filtroEst', 'filtroCat'));
    }

    public function show(Reporte $reporte)
    {
        // Ciudadano solo puede ver sus propios reportes
        if (Auth::user()->rol === 'ciudadano' && $reporte->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este reporte.');
        }

        $estados = $reporte->estados()->with('user')->latest()->get();
        return view('reportes.show', compact('reporte', 'estados'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
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
            $base64String = 'data:'.$file->getClientMimeType().';base64,'.base64_encode(file_get_contents($file->getRealPath()));
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

        EstadoReporte::create([
            'reporte_id'      => $reporte->id,
            'user_id'         => Auth::id(),
            'estado_anterior' => 'Nuevo',
            'estado_nuevo'    => 'Pendiente',
            'comentario'      => 'Reporte creado.',
        ]);

        return redirect('/reportes')->with('success', 'Reporte enviado correctamente.');
    }

    public function edit(Reporte $reporte)
    {
        // Solo el dueño puede editar y solo si está Pendiente
        if ($reporte->user_id !== Auth::id() || $reporte->estado !== 'Pendiente') {
            abort(403, 'No puedes editar este reporte.');
        }

        $categorias = Categoria::orderBy('nombre')->get();
        return view('reportes.edit', compact('reporte', 'categorias'));
    }

    public function update(Request $request, Reporte $reporte)
    {
        if ($reporte->user_id !== Auth::id() || $reporte->estado !== 'Pendiente') {
            abort(403);
        }

        $request->validate([
            'titulo'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'required|string',
            'departamento' => 'required|in:La Paz,Cochabamba,Santa Cruz,Oruro,Potosí,Chuquisaca,Tarija,Beni,Pando',
            'gravedad'     => 'required|in:Baja,Media,Alta,Crítica',
        ]);

        $reporte->update([
            'titulo'       => $request->titulo,
            'categoria_id' => $request->categoria_id,
            'descripcion'  => $request->descripcion,
            'departamento' => $request->departamento,
            'gravedad'     => $request->gravedad,
        ]);

        return redirect('/reportes/'.$reporte->id)->with('success', 'Reporte actualizado correctamente.');
    }

    public function cambiarEstado(Request $request, Reporte $reporte)
    {
        $request->validate([
            'estado_nuevo' => 'required|in:Pendiente,En revisión,En proceso,Resuelto,Cerrado',
            'comentario'   => 'nullable|string',
        ]);

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
