<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Categoria;
use App\Models\EstadoReporte;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('reportes.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'required|string',
            'latitud'      => 'required|numeric',
            'longitud'     => 'required|numeric',
            'foto'         => 'required|image|max:10240',
        ]);

        $base64String = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $imageContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getClientMimeType();
            $base64String = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
        }

        Reporte::create([
            'user_id'      => $request->user()->id,
            'categoria_id' => $request->input('categoria_id'),
            'departamento' => $request->input('departamento'),
            'titulo'       => $request->input('titulo'),
            'descripcion'  => $request->input('descripcion'),
            'latitud'      => $request->input('latitud'),
            'longitud'     => $request->input('longitud'),
            'foto_base64'  => $base64String,
            'gravedad'     => $request->input('gravedad', 'leve'),
            'estado'       => 'pendiente',
            'fecha_incidente' => $request->input('fecha_incidente'),
        ]);

        return redirect()->route('reportes.create')->with('success', 'Reporte enviado correctamente.');
    }

    public function index(Request $request)
    {
        $query = Reporte::with(['user', 'categoria']);

        // Ciudadano solo ve los suyos
        if (Auth::user()->rol === 'ciudadano') {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('gravedad')) {
            $query->where('gravedad', $request->gravedad);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $reportes  = $query->latest()->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('reportes.index', compact('reportes', 'categorias'));
    }

    public function show($id)
    {
        $reporte = Reporte::with(['user', 'categoria', 'estadosReporte'])->findOrFail($id);

        return view('reportes.show', compact('reporte'));
    }

    // Método de Estefanía: cambiar estado del reporte
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado'     => 'required|in:pendiente,en_proceso,resuelto,rechazado',
            'comentario' => 'nullable|string|max:500',
        ]);

        $reporte = Reporte::findOrFail($id);

        // Actualizamos el estado en la tabla reportes
        $reporte->update(['estado' => $request->estado]);

        // Guardamos el historial
        EstadoReporte::create([
            'reporte_id' => $reporte->id,
            'user_id'    => Auth::id(),
            'estado'     => $request->estado,
            'comentario' => $request->comentario,
        ]);

        return redirect()->route('reportes.show', $reporte->id)
                         ->with('success', 'Estado actualizado correctamente.');
    }
}
