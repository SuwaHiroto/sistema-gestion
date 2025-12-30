<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\User;

class ClienteController extends Controller
{
    /**
     * Dashboard del Cliente.
     */
    public function index()
    {
        $user = Auth::user();

        // Evitar que el admin entre aquí por error
        if ($user->rol === 'admin') {
            return redirect()->route('dashboard');
        }

        // 1. Buscamos el perfil del cliente
        $cliente = Cliente::where('id_usuario', $user->id_usuario)->first();

        // 2. Si no tiene perfil completo, redirigir al formulario
        if (!$cliente || empty($cliente->nombres)) {
            return redirect()->route('cliente.complete.show');
        }

        // 3. Mostramos sus servicios ordenados por fecha
        $servicios = Servicio::where('id_cliente', $cliente->id_cliente)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.index', compact('servicios'));
    }

    /**
     * Lista de Clientes (Solo para el ADMIN)
     */
    public function indexAdmin(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $clientes = Cliente::with('usuario')
            // Filtros de estado y búsqueda
            ->when($request->get('ver_inactivos'), function ($query) {
                return $query;
            }, function ($query) {
                return $query->where('estado', true);
            })
            ->when($request->get('search'), function ($query, $search) {
                return $query->where('nombres', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Ver Detalle de un Servicio (Vista del Cliente)
     */
    public function show($id)
    {
        $user = Auth::user();
        $cliente = Cliente::where('id_usuario', $user->id_usuario)->firstOrFail();

        // Cargamos todas las relaciones necesarias
        $servicio = Servicio::with([
            'tecnico',
            'historial',
            'pagos',
            'materiales'
        ])
            ->where('id_cliente', $cliente->id_cliente)
            ->findOrFail($id);

        return view('cliente.show', compact('servicio'));
    }

    /**
     * Vistas para completar perfil (Primer ingreso)
     */
    public function showCompleteProfile()
    {
        return view('auth.complete-profile');
    }

    public function storeCompleteProfile(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
            'dni' => 'nullable|digits:8',
        ]);

        $user = Auth::user();

        Cliente::updateOrCreate(
            ['id_usuario' => $user->id_usuario],
            [
                'nombres' => $request->nombres,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'correo' => $user->email,
                'estado' => true
            ]
        );

        return redirect()->route('cliente.index')->with('success', '¡Perfil actualizado! Ya puedes solicitar servicios.');
    }

    /**
     * Guardar nueva solicitud de servicio (Cliente)
     */
    public function store(Request $request)
    {
$request->validate([
            'descripcion' => 'required|min:5' 
        ]);

        // Crea el servicio y marca la fecha exacta de solicitud
        $servicio = Servicio::create([
            'id_cliente' => Auth::user()->cliente->id_cliente,
            'descripcion_solicitud' => $request->descripcion,
            'estado' => 'PENDIENTE',       // Pasa directo a PENDIENTE
            'fecha_solicitud' => now(),    // <--- ESTO LLENA EL CAMPO
        ]);

        return back()->with('success', 'Tu solicitud ha sido enviada correctamente.');
    }

    /**
     * Crear Cliente desde Admin
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'nombres' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'cliente',
                'activo' => true,
            ]);

            // Forzamos la creación/actualización del perfil de cliente
            $cliente = Cliente::firstOrNew(['id_usuario' => $user->id_usuario]);

            $cliente->id_usuario = $user->id_usuario;
            $cliente->nombres = $request->nombres;
            $cliente->telefono = $request->telefono;
            $cliente->direccion = $request->direccion;
            $cliente->correo = $request->email;
            $cliente->estado = $request->estado ?? true;

            $cliente->save();
        });

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente');
    }

    /**
     * Actualizar Cliente desde Admin
     */
    public function updateAdmin(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $usuario = User::findOrFail($cliente->id_usuario);

        $request->validate([
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'nombres' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $cliente, $usuario) {
            // Actualizar Usuario (Login)
            $userData = [
                'email' => $request->email,
                'activo' => $request->estado
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $usuario->update($userData);

            // Actualizar Perfil Cliente
            $cliente->update([
                'nombres' => $request->nombres,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'correo' => $request->email,
                'estado' => $request->estado
            ]);
        });

        return redirect()->route('clientes.index')->with('success', 'Datos del cliente actualizados.');
    }

    /**
     * Baja lógica (Soft Delete)
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $usuario = User::find($cliente->id_usuario);

        DB::transaction(function () use ($cliente, $usuario) {
            // 1. Desactivamos al cliente
            $cliente->estado = false;
            $cliente->save();

            // 2. Bloqueamos el login
            if ($usuario) {
                $usuario->activo = false;
                $usuario->save();
            }
        });

        return redirect()->route('clientes.index')->with('success', 'Cliente dado de baja. Acceso revocado.');
    }
}
