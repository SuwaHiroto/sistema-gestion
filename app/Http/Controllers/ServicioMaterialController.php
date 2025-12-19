<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicioMaterialesController extends Controller
{
    public function store(Request $request, $idServicio)
    {
        $request->validate([
            'id_material' => 'required|exists:materiales,id_material',
            'cantidad' => 'required|numeric|min:0.1',
            'precio_unitario' => 'required|numeric|min:0', // El técnico define el precio real
        ]);

        $servicio = Servicio::findOrFail($idServicio);

        // Usamos attach para insertar en la tabla pivote
        $servicio->materiales()->attach($request->id_material, [
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio_unitario,
            // 'created_at' se llena solo si configuraste withTimestamps() en el modelo
        ]);

        return back()->with('success', 'Material agregado al servicio.');
    }

    public function destroy($idServicio, $idDetalle)
    {

        DB::table('servicio__materiales')->where('id_detalle', $idDetalle)->delete();

        return back()->with('success', 'Material eliminado.');
    }
}
