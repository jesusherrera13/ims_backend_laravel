<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function index()
    {
        return response()->json(Estado::all(), 200);
    }


    public function create(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'pais_id' => 'required|integer',
        ]);

        $estado = Estado::create([
            'nombre' => $fields['nombre'],
            'pais_id' => $fields['pais_id'],
        ]);

        return response()->json($estado, 201);
    }


    public function update(Request $request, Estado $estado)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'pais_id' => 'required|integer',
        ]);

        $estado->update($fields);

        return response()->json($estado, 200);
    }

    public function destroy(Estado $estado)
    {
        $estado->delete();
        return response()->json(['message' => 'Estado eliminado'], 200);
    }
}