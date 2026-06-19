<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Throttle: máx 5 intentos por IP en 60 segundos
        $throttleKey = Str::lower($request->input('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Demasiados intentos fallidos. Inténtalo de nuevo en {$seconds} segundos.",
            ]);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ((int) $user->is_active === 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                RateLimiter::hit($throttleKey);
                return back()->withErrors([
                    'email' => 'Tu cuenta ha sido bloqueada.',
                ]);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            if ($user->rol === 'administrador') {
                return redirect()->intended('/usuarios');
            } elseif ($user->rol === 'supervisor') {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/reportes');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol'       => 'ciudadano',
            'is_active' => 1,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/reportes');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
