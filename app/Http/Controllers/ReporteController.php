<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Categoria;

class ReporteController extends Controller
{
    public function create()
    {
        // Traemos las categorías para el select del formulario
        $categorias = Categoria::all();
        return view('reportes.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'foto' => 'required|image|max:10240', // Máximo 2MB
        ]);

        $base64String = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $imageContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getClientMimeType();
            $base64String = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
        }

        Reporte::create([
            'user_id' => $request->user()->id,
            'categoria_id' => $request->input('categoria_id'),
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'latitud' => $request->input('latitud'),
            'longitud' => $request->input('longitud'),
            'foto_base64' => $base64String,
        ]);

        return redirect()->route('reportes.create')->with('success', 'Reporte enviado correctamente con geolocalización.');
    }
}