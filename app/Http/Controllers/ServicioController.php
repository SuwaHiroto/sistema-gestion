<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Tecnico;
use App\Models\Material;
use App\Models\Cliente;
use App\Models\HistorialServicio; // Asegúrate de importar el modelo de historial correcto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    /**
     * Listado de servicios
     */
    public function index()
    {
        $user = Auth::user();
        $query = Servicio::with(['cliente', 'tecnico'])->orderBy('created_at', 'desc');

        if ($user->rol === 'tecnico') {
            if (!$user->tecnico) abort(403, 'Perfil técnico incompleto');
            $query->where('id_tecnico', $user->tecnico->id_tecnico);
        } elseif ($user->rol === 'cliente') {
            if (!$user->cliente) abort(403, 'Perfil cliente incompleto');
            $query->where('id_cliente', $user->cliente->id_cliente);
        }

        $servicios = $query->paginate(10);
        return view('admin.servicios.index', compact('servicios'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $clientes = Cliente::all();
        $tecnicos = Tecnico::where('estado', true)->get();
        $materiales = Material::all();
        return view('admin.servicios.create', compact('clientes', 'tecnicos', 'materiales'));
    }

    /**
     * Guardar nuevo servicio
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion_solicitud' => 'required|min:5',
            'monto_cotizado' => 'numeric|nullable',
        ]);

        $user = Auth::user();
        $idCliente = $user->rol === 'cliente' ? $user->cliente->id_cliente : $request->id_cliente;

        DB::transaction(function () use ($request, $user, $idCliente) {
            $servicio = Servicio::create([
                'id_cliente' => $idCliente,
                'id_tecnico' => $request->id_tecnico,
                'descripcion_solicitud' => $request->descripcion_solicitud,
                'estado' => ($request->id_tecnico) ? 'PENDIENTE' : 'PENDIENTE',
                'fecha_solicitud' => now(),
                'fecha_inicio' => $request->fecha_inicio,
                'monto_cotizado' => $request->monto_cotizado ?? 0,
            ]);

            // Guardar Materiales
            if ($request->has('materiales')) {
                $syncData = [];
                foreach ($request->materiales as $mat) {
                    if (isset($mat['id']) && $mat['id']) {
                        $syncData[$mat['id']] = [
                            'cantidad' => $mat['cantidad'],
                            'precio_unitario' => $mat['precio']
                        ];
                    }
                }
                $servicio->materiales()->sync($syncData);
            }

            // Historial
            HistorialServicio::create([
                'id_servicio' => $servicio->id_servicio,
                'id_usuario_responsable' => $user->id_usuario,
                'estado_nuevo' => $servicio->estado,
                'comentario' => 'Creación inicial',
                'fecha_cambio' => now()
            ]);
        });

        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente');
    }

    /**
     * Ver detalle
     */
    public function show($id)
    {
        $servicio = Servicio::with(['cliente', 'tecnico', 'pagos', 'historial.responsable', 'materiales'])->findOrFail($id);
        $tecnicos = Tecnico::where('estado', true)->get();
        return view('admin.servicios.show', compact('servicio', 'tecnicos'));
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $servicio = Servicio::with(['cliente', 'materiales'])->findOrFail($id);
        $tecnicos = Tecnico::where('estado', true)->get();
        $materiales = Material::all();

        return view('admin.servicios.edit', compact('servicio', 'tecnicos', 'materiales'));
    }

    /**
     * Actualizar servicio (Lógica corregida)
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        // --- PRIORIDAD 1: EDICIÓN GENERAL (Desde edit.blade.php) ---
        if ($request->has('modo_edicion') && $request->modo_edicion == 'general') {

            // 1. Actualizar datos básicos
            $servicio->update([
                'descripcion_solicitud' => $request->descripcion_solicitud,
                'monto_cotizado' => $request->monto_cotizado,
                'costo_final_real' => $request->costo_final_real,
                'id_tecnico' => $request->id_tecnico,
                'estado' => $request->estado,
            ]);

            // 2. Sincronizar Materiales
            if ($request->has('materiales')) {
                $syncData = [];
                foreach ($request->materiales as $mat) {
                    if (isset($mat['id']) && $mat['id']) {
                        $syncData[$mat['id']] = [
                            'cantidad' => $mat['cantidad'],
                            'precio_unitario' => $mat['precio']
                        ];
                    }
                }
                $servicio->materiales()->sync($syncData);
            } else {
                // Si borraron todos los materiales, limpiar la tabla pivote
                $servicio->materiales()->detach();
            }

            // 3. Registrar Historial
            $this->logHistorial($servicio, 'Edición completa de servicio');

            return redirect()->route('servicios.show', $servicio->id_servicio)->with('success', 'Datos actualizados correctamente');
        }

        // --- PRIORIDAD 2: ASIGNACIÓN RÁPIDA (Desde show.blade.php) ---
        if ($request->has('id_tecnico') && !$request->has('modo_edicion')) {
            $servicio->id_tecnico = $request->id_tecnico;
            $servicio->estado = 'PENDIENTE';
            $servicio->save();
            $this->logHistorial($servicio, 'Técnico Asignado');

            return back()->with('success', 'Técnico asignado');
        }

        // --- PRIORIDAD 3: CAMBIO DE ESTADO RÁPIDO (Desde show.blade.php) ---
        if ($request->has('estado') && !$request->has('modo_edicion')) {
            $servicio->estado = $request->estado;
            $servicio->save();
            $this->logHistorial($servicio, "Cambio a {$request->estado}");

            return back()->with('success', 'Estado actualizado');
        }

        return back();
    }

    // Helper para no repetir código de historial
    private function logHistorial($servicio, $comentario)
    {
        HistorialServicio::create([
            'id_servicio' => $servicio->id_servicio,
            'id_usuario_responsable' => Auth::id(),
            'estado_nuevo' => $servicio->estado,
            'comentario' => $comentario,
            'fecha_cambio' => now()
        ]);
    }
}
