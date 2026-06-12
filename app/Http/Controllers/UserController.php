<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $usuarios = User::when($buscar, function($q) use ($buscar) {
            $q->where('name', 'LIKE', '%'.$buscar.'%')
              ->orWhere('email', 'LIKE', '%'.$buscar.'%');
        })->paginate(10);

        return view('users.index', compact('usuarios', 'buscar'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rol'      => 'required|in:administrador,supervisor,ciudadano',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol'       => $request->rol,
            'is_active' => 1,
        ]);

        return redirect('/usuarios')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'rol'   => 'required|in:administrador,supervisor,ciudadano',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'rol'   => $request->rol,
        ]);

        return redirect('/usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    public function toggleActivo(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return redirect('/usuarios')->with('success', 'Estado del usuario actualizado.');
    }
}