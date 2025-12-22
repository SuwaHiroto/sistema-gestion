<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TecnicoController extends Controller
{
    // GET: Listar técnicos (Filtrando activos por defecto)
    public function index(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        // Filtramos: Por defecto solo activos, salvo que pidan ver todos
        $tecnicos = Tecnico::with('usuario')
            ->when($request->get('ver_inactivos'), function ($query) {
                return $query; // Si piden inactivos, devolvemos todo
            }, function ($query) {
                return $query->where('estado', true); // Por defecto solo activos
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.tecnicos.index', compact('tecnicos'));
    }

    // POST: Registrar nuevo técnico
    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'nombres' => 'required|string',
            'apellido_paterno' => 'required|string',
            'dni' => 'required|digits:8|unique:tecnicos,dni',
            'especialidad' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'rol' => 'tecnico',
                    'activo' => true,
                    'name' => $request->nombres
                ]);

                Tecnico::create([
                    'id_usuario' => $user->id_usuario,
                    'nombres' => $request->nombres,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno, // Puede ser null si no lo envían
                    'dni' => $request->dni,
                    'telefono' => $request->telefono,
                    'especialidad' => $request->especialidad,
                    'estado' => true
                ]);
            });

            return redirect()->route('tecnicos.index')->with('success', 'Técnico registrado correctamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    // PUT: Actualizar técnico
    public function update(Request $request, $id)
    {
        $tecnico = Tecnico::findOrFail($id);
        $usuario = User::findOrFail($tecnico->id_usuario);

        $request->validate([
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'nombres' => 'required|string',
            'dni' => 'required|digits:8|unique:tecnicos,dni,' . $tecnico->id_tecnico . ',id_tecnico',
        ]);

        DB::transaction(function () use ($request, $tecnico, $usuario) {
            // Actualizar Login
            $usuario->update([
                'email' => $request->email,
                'name' => $request->nombres,
                // Si el técnico estaba inactivo y lo editan, NO lo activamos automáticamente aquí
                // a menos que tú quieras. Por seguridad mantenemos el estado actual del técnico
                // o el que venga en el request si añades un input de estado.
            ]);

            if ($request->filled('password')) {
                $usuario->update(['password' => Hash::make($request->password)]);
            }

            // Actualizar Perfil
            $tecnico->update([
                'nombres' => $request->nombres,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'dni' => $request->dni,
                'telefono' => $request->telefono,
                'especialidad' => $request->especialidad,
            ]);
        });

        return redirect()->route('tecnicos.index')->with('success', 'Datos actualizados.');
    }

    /**
     * BAJA LÓGICA: Desactiva el técnico y su acceso al sistema.
     */
    public function destroy($id)
    {
        $tecnico = Tecnico::findOrFail($id);
        $usuario = User::find($tecnico->id_usuario);

        DB::transaction(function () use ($tecnico, $usuario) {
            // 1. Desactivar Perfil Técnico
            $tecnico->estado = false;
            $tecnico->save();

            // 2. Bloquear Acceso (Login)
            if ($usuario) {
                $usuario->activo = false;
                $usuario->save();
            }
        });

        return redirect()->route('tecnicos.index')->with('success', 'Técnico dado de baja. Acceso revocado.');
    }
}
