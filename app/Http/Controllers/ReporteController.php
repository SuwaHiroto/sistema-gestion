<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Tecnico;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // Detectamos si es SQLite o MySQL para usar la función de fecha correcta
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        // SQLite usa strftime('%m', created_at), MySQL usa MONTH(created_at)
        $monthFunction = $isSqlite ? "strftime('%m', created_at)" : "MONTH(created_at)";

        // 1. Ingresos agrupados por mes
        $ingresosPorMes = Pago::select(
            DB::raw("$monthFunction as mes"),
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(monto) as total')
        )
            ->where('validado', true)
            ->whereYear('created_at', date('Y'))
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->get();

        // 2. Técnicos con más servicios finalizados
        $topTecnicos = Tecnico::withCount(['servicios' => function ($query) {
            $query->where('estado', 'FINALIZADO');
        }])
            ->orderBy('servicios_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.reportes.index', compact('ingresosPorMes', 'topTecnicos'));
    }
}
