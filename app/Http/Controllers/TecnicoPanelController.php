<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\Material;
use App\Models\HistorialServicio;
use App\Models\Pago; // Importamos el modelo de Pagos

class TecnicoPanelController extends Controller
{
    /**
     * Dashboard del Técnico: Muestra sus servicios asignados.
     */
    public function index()
    {
        $user = Auth::user();

        // Verificamos si tiene perfil de técnico
        if (!$user->tecnico) {
            return redirect()->route('dashboard')->with('error', 'No tienes perfil de técnico asignado.');
        }

        // CORRECCIÓN: Usamos CASE en lugar de FIELD para compatibilidad con SQLite
        $ordenPersonalizado = "CASE estado 
            WHEN 'EN_PROCESO' THEN 1 
            WHEN 'APROBADO' THEN 2 
            WHEN 'COTIZANDO' THEN 3 
            WHEN 'FINALIZADO' THEN 4 
            ELSE 5 
        END";

        // Traemos los servicios asignados a ESTE técnico
        // Ordenamos por prioridad de estado y luego por fecha
        $servicios = Servicio::where('id_tecnico', $user->tecnico->id_tecnico)
            ->whereIn('estado', ['COTIZANDO', 'APROBADO', 'EN_PROCESO', 'FINALIZADO'])
            ->orderByRaw($ordenPersonalizado)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return view('tecnico.index', compact('servicios'));
    }

    /**
     * Ver detalle de un trabajo específico.
     */
    public function show($id)
    {
        $user = Auth::user();
        // Cargamos pagos también para mostrar el historial de cobros de este servicio
        $servicio = Servicio::with(['cliente', 'materiales', 'pagos'])->findOrFail($id);

        // Seguridad: Verificar que el servicio sea de este técnico
        if ($servicio->id_tecnico !== $user->tecnico->id_tecnico) {
            abort(403, 'No tienes permiso para ver este servicio.');
        }

        $materialesDisponibles = Material::all(); // Para que pueda agregar materiales

        return view('tecnico.show', compact('servicio', 'materialesDisponibles'));
    }

    /**
     * Actualizar estado del servicio (Iniciar/Finalizar) o agregar materiales.
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);
        $user = Auth::user();

        // 1. Cambio de Estado (Iniciar / Finalizar)
        if ($request->has('estado')) {
            $nuevoEstado = $request->estado;

            // Validaciones de flujo lógico
            if ($nuevoEstado == 'EN_PROCESO' && $servicio->estado != 'APROBADO' && $servicio->estado != 'COTIZANDO') {
                return back()->with('error', 'El servicio debe estar Aprobado para iniciar.');
            }

            $servicio->estado = $nuevoEstado;
            $servicio->save();

            // Guardar historial
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
                'precio' => 'required|numeric|min:0' // Validamos el precio que viene del formulario
            ]);

            // No es necesario buscar el material para el precio, usamos el del request
            // $material = Materiales::find($request->id_material); 

            // Agregamos a la tabla pivote usando el precio enviado por el técnico
            $servicio->materiales()->attach($request->id_material, [
                'cantidad' => $request->cantidad,
                'precio_unitario' => $request->precio // Usamos el precio del input
            ]);
        }

        return back()->with('success', 'Servicio actualizado correctamente.');
    }

    /**
     * Registrar un pago cobrado por el técnico.
     */
    public function storePago(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);
        $user = Auth::user();

        // Seguridad: Verificar pertenencia
        if ($servicio->id_tecnico !== $user->tecnico->id_tecnico) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'monto' => 'required|numeric|min:0.1',
            'tipo' => 'required|string'
        ]);

        // Registrar pago (Siempre NO validado al principio)
        Pago::create([
            'id_servicio' => $servicio->id_servicio,
            'id_usuario_registra' => $user->id_usuario,
            'monto' => $request->monto,
            'tipo' => $request->tipo,
            'validado' => false // Requiere confirmación del Admin
        ]);

        return back()->with('success', 'Cobro registrado. Pendiente de validación por administración.');
    }
}
