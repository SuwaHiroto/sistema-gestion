<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    public function index()
    {
        // Traemos usuarios que sean admin o tecnico
        $usuarios = User::whereIn('rol', ['admin', 'tecnico'])->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

public function store(Request $request)
    {
        // 1. Validaciones
        $request->validate([
            'nombres' => 'required|string', // <--- CORREGIDO (Plural)
            'apellido_paterno' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'dni' => 'required|unique:tecnicos,dni',
            'password' => 'required|min:8',
            'rol' => 'required|in:admin,tecnico'
        ]);

        // 2. Transacción
        DB::transaction(function () use ($request) {

            // A. Crear Login
            $usuario = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
                'activo' => true,
            ]);

            // B. Si es Técnico, crear perfil
            if ($request->rol === 'tecnico') {
                Tecnico::create([
                    'id_usuario' => $usuario->id_usuario,
                    // CORREGIDO: Asignamos 'nombres' (plural) a la columna 'nombres'
                    'nombres' => $request->nombres,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'dni' => $request->dni,
                    'especialidad' => $request->especialidad ?? 'General',
                    'telefono' => $request->telefono,
                ]);
            }
        });

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario y perfil creados correctamente.');
    }
}
