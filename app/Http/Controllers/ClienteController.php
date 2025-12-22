<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;  // Modelo en Plural
use App\Models\Servicio; // Modelo en Plural
use App\Models\User;      // Necesario para el admin

class ClienteController extends Controller
{
    /**
     * Dashboard del Cliente (Con redirección forzada).
     * ESTE ES SOLO PARA EL ROL 'CLIENTE'
     */
    public function index()
    {
        $user = Auth::user();

        // Si por error entra un admin aquí, lo mandamos a su dashboard
        if ($user->rol === 'admin') {
            return redirect()->route('dashboard');
        }

        // 1. Buscamos el perfil del cliente asociado al usuario
        $cliente = Cliente::where('id_usuario', $user->id_usuario)->first();

        // 2. Lógica de Redirección:
        if (!$cliente || empty($cliente->nombres)) {
            return redirect()->route('cliente.complete.show');
        }

        // 3. Si ya tiene perfil, mostramos su Dashboard con sus servicios
        $servicios = Servicio::where('id_cliente', $cliente->id_cliente)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.index', compact('servicios'));
    }

    /**
     * Lista de Clientes para el ADMINISTRADOR
     * ESTE ES EL QUE USA LA VISTA 'admin.clientes.index'
     */
    // Asegúrate de importar Request arriba: use Illuminate\Http\Request;

    public function indexAdmin(Request $request)
    {
        // 1. Seguridad: Solo admin entra
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        // 2. Consulta con Filtro Inteligente
        $clientes = Cliente::with('usuario')
            // AQUÍ ESTÁ LA CLAVE:
            ->when($request->get('ver_inactivos'), function ($query) {
                // Opción A: Si presionaste "Ver Historial", devuelve TODOS (activos e inactivos)
                return $query;
            }, function ($query) {
                // Opción B (Por defecto): Solo trae los que tienen estado = 1 (TRUE)
                return $query->where('estado', true);
            })
            // Filtro de búsqueda (si escribiste algo en el buscador)
            ->when($request->get('search'), function ($query, $search) {
                return $query->where('nombres', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.clientes.index', compact('clientes'));
    }



    public function show($id)
    {
        $user = Auth::user();
        $cliente = Cliente::where('id_usuario', $user->id_usuario)->firstOrFail();

        $servicio = Servicio::with([
            'tecnico',           // <--- CAMBIO AQUÍ: Singular
            'historial',
            'pagos',
            'materiales'
        ])
            ->where('id_cliente', $cliente->id_cliente)
            ->findOrFail($id);

        return view('cliente.show', compact('servicio'));
    }

    /**
     * Muestra el formulario para completar datos.
     */

    public function showCompleteProfile()
    {
        return view('auth.complete-profile');
    }

    /**
     * Guarda los datos del perfil y redirige al dashboard.
     */
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
     * Guardar nueva solicitud de servicio (Cliente).
     */
    public function store(Request $request)
    {
        $request->validate(['descripcion_solicitud' => 'required|min:5']);

        $user = Auth::user();
        $cliente = Cliente::where('id_usuario', $user->id_usuario)->first();

        Servicio::create([
            'id_cliente' => $cliente->id_cliente,
            'descripcion_solicitud' => $request->descripcion_solicitud,
            'estado' => 'PENDIENTE',
            'fecha_solicitud' => now(),
        ]);

        return back()->with('success', 'Tu solicitud ha sido enviada correctamente.');
    }
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'nombres' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'rol' => 'cliente',
                'activo' => true,
                'name' => $request->nombres
            ]);

            // El evento booted ya crea el cliente, pero lo actualizamos con los datos extra
            $cliente = Cliente::where('id_usuario', $user->id_usuario)->first();
            $cliente->update([
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'estado' => $request->estado ?? true
            ]);
        });

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente');
    }

    /**
     * Update para Admin
     */
    public function updateAdmin(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $usuario = User::findOrFail($cliente->id_usuario);

        $request->validate([
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'nombres' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $cliente, $usuario) {
            $usuario->update([
                'email' => $request->email,
                'name' => $request->nombres,
                'activo' => $request->estado // Si desactivan cliente, desactivan login
            ]);

            if ($request->filled('password')) {
                $usuario->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
            }

            $cliente->update([
                'nombres' => $request->nombres,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'estado' => $request->estado
            ]);
        });

        return redirect()->route('clientes.index')->with('success', 'Datos del cliente actualizados.');
    }
    /**
     * Baja lógica manual usando el campo 'estado'.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        // Buscamos el usuario asociado
        $usuario = User::find($cliente->id_usuario);

        \Illuminate\Support\Facades\DB::transaction(function () use ($cliente, $usuario) {
            // 1. Desactivamos al cliente en su tabla
            $cliente->estado = false;
            $cliente->save();

            // 2. Desactivamos el acceso al sistema (Login)
            if ($usuario) {
                $usuario->activo = false;
                $usuario->save();
            }
        });

        return redirect()->route('clientes.index')->with('success', 'Cliente dado de baja (Inactivo). Acceso revocado.');
    }
}
