<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function index()
    {
        return response()->json(Municipio::all(), 200);
    }


    public function create(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'estado_id' => 'required|integer',
        ]);

        $municipio = Municipio::create([
            'nombre' => $fields['nombre'],
            'estado_id' => $fields['estado_id'],
        ]);

        return response()->json($municipio, 201);
    }


    public function update(Request $request, Municipio $municipio)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'estado_id' => 'required|integer',
        ]);

        $municipio->update($fields);

        return response()->json($municipio, 200);
    }

    public function destroy(Municipio $municipio)
    {
        $municipio->delete();
        return response()->json(['message' => 'Municipio eliminado'], 200);
    }
}