<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente; // Modelo en Plural
use App\Models\Servicio; // Modelo en Plural
use App\Models\Pago; // Modelo en Plural
use App\Models\Tecnico; // Modelo en Plural
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal o redirige según el rol.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Redirección para TÉCNICOS
        if ($user->rol === 'tecnico') {
            // Si es técnico, NO debe ver el dashboard de admin.
            // Lo mandamos directo a su panel de trabajo.
            return redirect()->route('tecnico.index');
        }

        // 2. Redirección para CLIENTES
        if ($user->rol === 'cliente') {
            // Si es cliente, a su cuenta personal.
            return redirect()->route('cliente.index'); 
        }

        // 3. Lógica para ADMINISTRADOR (Si llega aquí, es porque es Admin)
        // Solo el admin debe ver estas estadísticas globales.
        
        $stats = [
            'clientes' => Cliente::count(),
            'total_servicios' => Servicio::count(),
            'pagos_hoy' => Pago::whereDate('created_at', Carbon::today())->sum('monto'),
            'pendientes' => Servicio::where('estado', 'PENDIENTE')->count(),
            'tecnicos' => Tecnico::where('estado', true)->count(),
        ];

        // Traemos los 5 servicios más recientes para la tabla del dashboard admin
        $recent_servicios = Servicio::with('cliente')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.index', compact('stats', 'recent_servicios'));
    }
}