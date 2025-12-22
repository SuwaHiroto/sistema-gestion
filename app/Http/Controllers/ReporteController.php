<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Tecnico;
use App\Models\Servicio; // <--- IMPORTANTE: Agregamos este modelo
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        // --- 1. CONFIGURACIÓN DE BASE DE DATOS ---
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        // SQLite usa strftime('%m'), MySQL usa MONTH()
        $monthFunction = $isSqlite ? "strftime('%m', created_at)" : "MONTH(created_at)";

        // --- 2. TARJETAS KPI (RESUMEN EJECUTIVO) ---
        // Total de dinero recaudado (Solo pagos validados)
        $totalIngresos = Pago::where('validado', true)->sum('monto');

        // Conteo de servicios según estado
        $serviciosFinalizados = Servicio::where('estado', 'FINALIZADO')->count();
        // Agrupamos todo lo que no esté finalizado ni cancelado como "Pendiente/En Proceso"
        $serviciosPendientes = Servicio::whereIn('estado', ['PENDIENTE', 'EN_PROCESO', 'APROBADO', 'COTIZANDO'])->count();


        // --- 3. DATOS PARA TABLA Y GRÁFICOS ---

        // Obtenemos los ingresos agrupados por mes
        $ingresosRaw = Pago::select(
            DB::raw("$monthFunction as mes"),
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(monto) as total')
        )
            ->where('validado', true)
            ->whereYear('created_at', date('Y'))
            ->groupBy('mes')
            ->orderBy('mes', 'asc') // Ordenamos ascendente primero para el gráfico
            ->get();

        // A. Preparamos datos para Chart.js (Ejes X e Y)
        $labelsGrafico = $ingresosRaw->map(function ($item) {
            // CORRECCIÓN:
            // 1. Usamos (int) para convertir el string "05" a número 5.
            // 2. Pasamos el día '1' como tercer parámetro para evitar errores si hoy es día 31 
            //    (Si hoy fuera 31 y seteas mes Febrero, Carbon saltaría a Marzo sin el día 1).
            return Carbon::create(null, (int) $item->mes, 1)->locale('es')->isoFormat('MMMM');
        });

        $dataGrafico = $ingresosRaw->pluck('total'); // Solo los montos

        // B. Datos para la Tabla (La invertimos para ver lo más reciente arriba)
        $ingresosPorMes = $ingresosRaw->sortByDesc('mes');


        // --- 4. TOP TÉCNICOS ---
        $topTecnicos = Tecnico::withCount(['servicios' => function ($query) {
            $query->where('estado', 'FINALIZADO');
        }])
            ->orderBy('servicios_count', 'desc')
            ->take(5)
            ->get();

        // --- 5. RETORNO A LA VISTA ---
        return view('admin.reportes.index', compact(
            'totalIngresos',
            'serviciosFinalizados',
            'serviciosPendientes',
            'labelsGrafico',
            'dataGrafico',
            'ingresosPorMes',
            'topTecnicos'
        ));
    }
}
