<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsuarios  = User::count();
        $totalReportes  = 0;
        $resueltos      = 0;
        $pendientes     = 0;

        return view('dashboard.index', compact(
            'totalUsuarios',
            'totalReportes',
            'resueltos',
            'pendientes'
        ));
    }
}
