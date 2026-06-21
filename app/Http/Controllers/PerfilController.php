<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Reporte;

// Perfil del usuario autenticado — Hecho por Keyra
class PerfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalReportes  = Reporte::where('user_id', $user->id)->count();
        $resueltos      = Reporte::where('user_id', $user->id)->where('estado', 'Resuelto')->count();
        $pendientes     = Reporte::where('user_id', $user->id)->where('estado', 'Pendiente')->count();

        return view('perfil.index', compact('user', 'totalReportes', 'resueltos', 'pendientes'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'                  => 'required|string|max:255',
            'password'              => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
    }
}
