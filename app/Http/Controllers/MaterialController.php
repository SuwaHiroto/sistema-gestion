<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material; // Modelo en Plural

class MaterialController extends Controller
{
    /**
     * Lista de materiales con opción de búsqueda.
     */
    public function index(Request $request)
    {
        $query = Material::query();

        // Buscador simple
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }

        $materiales = $query->orderBy('nombre', 'asc')->paginate(10);

        return view('admin.materiales.index', compact('materiales'));
    }

    /**
     * Guardar nuevo material.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad' => 'required|string',
            'precio_referencial' => 'required|numeric|min:0'
        ]);

        Material::create($request->all());

        return redirect()->route('materiales.index')->with('success', 'Material registrado correctamente');
    }

    /**
     * Actualizar material existente.
     */
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad' => 'required|string',
            'precio_referencial' => 'required|numeric|min:0'
        ]);

        $material->update($request->all());

        return redirect()->route('materiales.index')->with('success', 'Material actualizado correctamente');
    }
}
