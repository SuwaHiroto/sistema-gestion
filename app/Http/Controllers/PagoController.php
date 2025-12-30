<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index()
    {
        // 1. Lista de pagos paginada (para la tabla)
        $pagos = Pago::with(['servicio.cliente', 'registradoPor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // 2. Servicios pendientes para el selector
        $serviciosPendientes = Servicio::with('cliente')
            ->whereIn('estado', ['COTIZANDO', 'APROBADO', 'EN_PROCESO', 'FINALIZADO'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. CORRECCIÓN: Calcular el Total Global Validado (suma histórica real)
        $totalRecaudado = Pago::where('validado', true)->sum('monto');

        return view('admin.pagos.index', compact('pagos', 'serviciosPendientes', 'totalRecaudado'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Seguridad básica
        if ($user->rol === 'cliente') {
            return back()->withErrors(['error' => 'Acceso denegado.']);
        }

        $request->validate([
            'id_servicio' => 'required|exists:servicios,id_servicio',
            'monto' => 'required|numeric|min:0.1',
            'tipo' => 'required|string',
        ]);

        $servicio = Servicio::findOrFail($request->id_servicio);

        // Si es técnico, verificar que sea SU servicio
        if ($user->rol === 'tecnico') {
            if ($servicio->id_tecnico !== ($user->tecnico->id_tecnico ?? null)) {
                return back()->withErrors(['error' => 'Solo puedes registrar pagos de tus servicios asignados.']);
            }
        }

        // --- CÁLCULO DE DEUDA Y VALIDACIÓN ---
        $pagadoHastaAhora = $servicio->pagos()->sum('monto');

        // Si ya finalizó, usamos el costo real guardado.
        if ($servicio->costo_final_real > 0) {
            $costoTotal = $servicio->costo_final_real;
        } else {
            // Si no ha finalizado, calculamos la deuda estimada al vuelo
            $costoMateriales = $servicio->materiales->sum(function ($m) {
                return $m->pivot->cantidad * $m->pivot->precio_unitario;
            });
            $costoTotal = ($servicio->mano_obra ?? 0) + $costoMateriales;
        }

        // Opcional: Bloquear si pagan de más (puedes comentar este if si quieres permitir saldo a favor)
        if (($pagadoHastaAhora + $request->monto) > $costoTotal) {
            return back()->withErrors(['monto' => "El monto excede la deuda total calculada (S/ " . number_format($costoTotal, 2) . ")."]);
        }

        // Crear el pago
        Pago::create([
            'id_servicio' => $request->id_servicio,
            'id_usuario_registra' => $user->id_usuario,
            'monto' => $request->monto,
            'tipo' => $request->tipo,
            // Si es admin se valida automático, si es técnico queda pendiente
            'validado' => ($user->rol === 'admin'),
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado exitosamente.');
    }

    public function validar($id)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permisos para validar pagos.');
        }

        $pago = Pago::findOrFail($id);
        $pago->validado = true;
        $pago->save();

        return back()->with('success', 'Pago validado correctamente.');
    }
}
