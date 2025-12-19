<?php

namespace App\Http\Controllers;

use App\Models\Tecnico; // Plural
use App\Models\User;     // Singular (tu modelo de usuario)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TecnicoController extends Controller
{
    // GET: Listar técnicos (Para la vista de gestión)
    public function index()
    {
        // Solo Admin puede ver la lista de técnicos
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        // Traemos técnicos con su usuario
        $tecnicos = Tecnico::with('usuario')->get();

        return view('admin.tecnicos.index', compact('tecnicos'));
    }

    // POST: Registrar nuevo técnico
    public function store(Request $request)
    {
        // 1. Verificar Rol Admin
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo administradores pueden registrar técnicos');
        }

        // 2. Validar Datos
        $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'nombres' => 'required|string',
            'apellido_paterno' => 'required|string',
            'dni' => 'required|digits:8|unique:tecnicos,dni',
            'especialidad' => 'required|string',
            'telefono' => 'nullable|string'
        ]);

        try {
            // 3. Transacción para asegurar integridad
            DB::transaction(function () use ($request) {

                // A. Crear Usuario (Login)
                $nuevoUsuario = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'rol' => 'tecnico',
                    'activo' => true,
                    'name' => $request->nombres // Opcional, si tu tabla usuarios tiene 'name'
                ]);

                // B. Crear Técnico (Perfil) vinculado al ID del usuario
                Tecnico::create([
                    'id_usuario' => $nuevoUsuario->id_usuario,
                    'nombres' => $request->nombres,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'dni' => $request->dni,
                    'telefono' => $request->telefono,
                    'especialidad' => $request->especialidad,
                    'estado' => true
                ]);
            });

            return redirect()->route('tecnicos.index')->with('success', 'Técnico registrado correctamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'No se pudo registrar: ' . $e->getMessage()]);
        }
    }

    // PUT: Actualizar técnico (Datos o Estado)
    public function update(Request $request, $id)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $tecnico = Tecnico::findOrFail($id);
        $usuario = User::findOrFail($tecnico->id_usuario);

        // Validaciones (el email y dni son únicos pero ignorando al usuario actual)
        $request->validate([
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'nombres' => 'required|string',
            'dni' => 'required|digits:8|unique:tecnicos,dni,' . $tecnico->id_tecnico . ',id_tecnico',
            'estado' => 'required|boolean'
        ]);

        try {
            DB::transaction(function () use ($request, $tecnico, $usuario) {
                // Actualizar Usuario (Login y Estado Activo)
                $usuario->update([
                    'email' => $request->email,
                    'activo' => $request->estado, // Si desactivas al técnico, desactivas su login
                    'name' => $request->nombres
                ]);

                // Si enviaron password nueva, la actualizamos
                if ($request->filled('password')) {
                    $usuario->update(['password' => Hash::make($request->password)]);
                }

                // Actualizar Perfil Técnico
                $tecnico->update([
                    'nombres' => $request->nombres,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'dni' => $request->dni,
                    'telefono' => $request->telefono,
                    'especialidad' => $request->especialidad,
                    'estado' => $request->estado
                ]);
            });

            return redirect()->route('tecnicos.index')->with('success', 'Datos del técnico actualizados.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }
}
