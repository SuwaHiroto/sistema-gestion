<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Tecnico;
use App\Models\Material;
use App\Models\Cliente;
use App\Models\HistorialServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
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

    public function create()
    {
        $clientes = Cliente::all();
        // Solo técnicos activos
        $tecnicos = Tecnico::where('estado', true)->get();
        $materiales = Material::all();
        return view('admin.servicios.create', compact('clientes', 'tecnicos', 'materiales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion_solicitud' => 'required|min:5',
            'mano_obra' => 'numeric|nullable|min:0', // CORREGIDO: Usamos mano_obra
        ]);

        $user = Auth::user();
        // Si lo crea un admin, usa el cliente del form. Si es cliente, usa su propio ID.
        $idCliente = $user->rol === 'cliente' ? $user->cliente->id_cliente : $request->id_cliente;

        DB::transaction(function () use ($request, $user, $idCliente) {

            // LÓGICA DE ESTADO: Si tiene técnico, nace APROBADO para que pueda trabajar
            $estadoInicial = ($request->id_tecnico) ? 'APROBADO' : 'PENDIENTE';

            $servicio = Servicio::create([
                'id_cliente' => $idCliente,
                'id_tecnico' => $request->id_tecnico,
                'descripcion_solicitud' => $request->descripcion_solicitud,
                'estado' => $estadoInicial,
                'fecha_solicitud' => now(),
                'fecha_inicio' => $request->fecha_inicio,
                'mano_obra' => $request->mano_obra ?? 0, // CORREGIDO
            ]);

            // Guardar Materiales iniciales (si el admin agrega alguno)
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

            // Historial inicial
            HistorialServicio::create([
                'id_servicio' => $servicio->id_servicio,
                'id_usuario_responsable' => $user->id_usuario,
                'estado_nuevo' => $servicio->estado,
                'comentario' => 'Servicio registrado en sistema',
                'fecha_cambio' => now()
            ]);
        });

        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente');
    }

    public function show($id)
    {
        $servicio = Servicio::with(['cliente', 'tecnico', 'pagos', 'historial.responsable', 'materiales'])->findOrFail($id);
        $tecnicos = Tecnico::where('estado', true)->get();
        return view('admin.servicios.show', compact('servicio', 'tecnicos'));
    }

    public function edit($id)
    {
        $servicio = Servicio::with(['cliente', 'materiales'])->findOrFail($id);
        $tecnicos = Tecnico::where('estado', true)->get();
        $materiales = Material::all();

        return view('admin.servicios.edit', compact('servicio', 'tecnicos', 'materiales'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);
        $user = Auth::user();

        // --- MODO 1: EDICIÓN COMPLETA (Desde edit.blade.php) ---
        if ($request->has('modo_edicion') && $request->modo_edicion == 'general') {

            $servicio->update([
                'descripcion_solicitud' => $request->descripcion_solicitud,
                'mano_obra' => $request->mano_obra, // CORREGIDO
                'costo_final_real' => $request->costo_final_real,
                'id_tecnico' => $request->id_tecnico,
                'estado' => $request->estado,
            ]);

            // Sincronizar materiales
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
                $servicio->materiales()->detach();
            }

            $this->logHistorial($servicio, 'Edición administrativa completa');
            return redirect()->route('servicios.show', $servicio->id_servicio)->with('success', 'Datos actualizados');
        }

        // --- MODO 2: ASIGNACIÓN RÁPIDA DE TÉCNICO (Desde show.blade.php) ---
        if ($request->has('id_tecnico') && !$request->has('modo_edicion')) {
            $servicio->id_tecnico = $request->id_tecnico;
            // Al asignar, lo aprobamos para que el técnico pueda iniciar
            $servicio->estado = 'APROBADO';
            $servicio->save();
            $this->logHistorial($servicio, 'Técnico Asignado y Servicio Aprobado');

            return back()->with('success', 'Técnico asignado correctamente');
        }

        // --- MODO 3: CAMBIO DE ESTADO RÁPIDO ---
        if ($request->has('estado') && !$request->has('modo_edicion')) {
            $servicio->estado = $request->estado;
            $servicio->save();
            $this->logHistorial($servicio, "Cambio manual a {$request->estado}");

            return back()->with('success', 'Estado actualizado');
        }

        return back();
    }

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
