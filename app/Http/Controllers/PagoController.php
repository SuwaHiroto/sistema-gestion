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
        $pagos = Pago::with(['servicio.cliente', 'registradoPor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $serviciosPendientes = Servicio::with('cliente')
            ->whereIn('estado', ['COTIZANDO', 'APROBADO', 'EN_PROCESO', 'FINALIZADO'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pagos.index', compact('pagos', 'serviciosPendientes'));
    }

    public function store(Request $request)
    {
        // ... (Tu código existente del método store) ...
        $user = Auth::user();

        if ($user->rol === 'cliente') {
            return back()->withErrors(['error' => 'Acceso denegado.']);
        }

        $request->validate([
            'id_servicio' => 'required|exists:servicios,id_servicio',
            'monto' => 'required|numeric|min:0.1',
            'tipo' => 'required|string',
        ]);

        $servicio = Servicio::find($request->id_servicio);

        if ($user->rol === 'tecnico') {
            if ($servicio->id_tecnico !== ($user->tecnico->id_tecnico ?? null)) {
                return back()->withErrors(['error' => 'Solo puedes registrar pagos de tus servicios asignados.']);
            }
        }

        // Validación de saldo
        $pagadoHastaAhora = $servicio->pagos()->sum('monto');
        $costoTotal = $servicio->costo_final_real > 0 ? $servicio->costo_final_real : $servicio->monto_cotizado;

        if (($pagadoHastaAhora + $request->monto) > $costoTotal) {
            return back()->withErrors(['monto' => "El monto excede la deuda pendiente."]);
        }

        Pago::create([
            'id_servicio' => $request->id_servicio,
            'id_usuario_registra' => $user->id_usuario,
            'monto' => $request->monto,
            'tipo' => $request->tipo,
            'validado' => ($user->rol === 'admin'),
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado exitosamente.');
    }

    /**
     * NUEVO MÉTODO: Validar un pago pendiente.
     */
    public function validar($id)
    {
        // Solo admin puede validar
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permisos para validar pagos.');
        }

        $pago = Pago::findOrFail($id);

        // Cambiamos el estado a validado (true = 1)
        $pago->validado = true;
        $pago->save();

        return back()->with('success', 'Pago validado correctamente.');
    }
}
