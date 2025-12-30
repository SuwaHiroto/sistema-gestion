<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\Material;
use App\Models\HistorialServicio;
use App\Models\Pago;

class TecnicoPanelController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->tecnico) {
            return redirect()->route('dashboard')->with('error', 'No tienes perfil de técnico asignado.');
        }

        // Ordenamos para que lo más urgente ("En Proceso") salga primero
        $ordenPersonalizado = "CASE estado 
            WHEN 'EN_PROCESO' THEN 1 
            WHEN 'APROBADO' THEN 2 
            WHEN 'PENDIENTE' THEN 3 
            WHEN 'FINALIZADO' THEN 4 
            ELSE 5 
        END";

        $servicios = Servicio::where('id_tecnico', $user->tecnico->id_tecnico)
            ->whereIn('estado', ['PENDIENTE', 'COTIZANDO', 'APROBADO', 'EN_PROCESO', 'FINALIZADO'])
            ->orderByRaw($ordenPersonalizado)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return view('tecnico.index', compact('servicios'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $servicio = Servicio::with(['cliente', 'materiales', 'pagos'])->findOrFail($id);

        if ($servicio->id_tecnico !== $user->tecnico->id_tecnico) {
            abort(403, 'No tienes permiso para ver este servicio.');
        }

        $materialesDisponibles = Material::all();

        return view('tecnico.show', compact('servicio', 'materialesDisponibles'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);
        $user = Auth::user();

        // 1. Cambio de Estado
        if ($request->has('estado')) {
            $nuevoEstado = $request->estado;

            // Validación: Solo se puede iniciar si está APROBADO o PENDIENTE/COTIZANDO (según tu flujo)
            // Aquí lo dejamos flexible para evitar bloqueos

            // A) INICIO
            if ($nuevoEstado == 'EN_PROCESO' && is_null($servicio->fecha_inicio)) {
                $servicio->fecha_inicio = now();
            }

            // B) FINALIZAR
            if ($nuevoEstado == 'FINALIZADO') {
                $servicio->fecha_fin = now();

                // 1. Costo Materiales
                $costoMateriales = $servicio->materiales->sum(function ($m) {
                    return $m->pivot->cantidad * $m->pivot->precio_unitario;
                });

                // 2. Mano de Obra (Si el técnico la ajustó o viene del admin)
                if ($request->has('mano_obra')) {
                    $servicio->mano_obra = $request->mano_obra;
                }

                $manoObra = $servicio->mano_obra ?? 0;

                // 3. Costo Total
                $servicio->costo_final_real = $manoObra + $costoMateriales;
            }

            $servicio->estado = $nuevoEstado;
            $servicio->save();

            HistorialServicio::create([
                'id_servicio' => $servicio->id_servicio,
                'id_usuario_responsable' => $user->id_usuario,
                'estado_nuevo' => $nuevoEstado,
                'comentario' => "Cambio de estado a $nuevoEstado por el técnico.",
                'fecha_cambio' => now()
            ]);
        }

        // 2. Agregar Materiales
        if ($request->has('agregar_material')) {
            $request->validate([
                'id_material' => 'required|exists:materiales,id_material',
                'cantidad' => 'required|numeric|min:0.1',
                'precio' => 'required|numeric|min:0'
            ]);

            $servicio->materiales()->attach($request->id_material, [
                'cantidad' => $request->cantidad,
                'precio_unitario' => $request->precio
            ]);
        }

        return back()->with('success', 'Servicio actualizado correctamente.');
    }

    // --- AQUÍ ESTABA EL ERROR (FALTABA EL CÓDIGO) ---
    public function storePago(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);
        $user = Auth::user();

        // Seguridad: Verificar que el servicio pertenece al técnico
        if ($servicio->id_tecnico !== $user->tecnico->id_tecnico) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'monto' => 'required|numeric|min:0.1',
            'tipo' => 'required|string'
        ]);

        Pago::create([
            'id_servicio' => $servicio->id_servicio,
            'id_usuario_registra' => $user->id_usuario,
            'monto' => $request->monto,
            'tipo' => $request->tipo,
            'validado' => false // Los pagos del técnico requieren validación de admin
        ]);

        return back()->with('success', 'Cobro registrado. Pendiente de validación por administración.');
    }
}
