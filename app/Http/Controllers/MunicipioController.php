<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function index()
{
    $municipios = Municipio::with('estado')
        ->select('id', 'nombre', 'codigo', 'estado_id')
        ->get()
        ->map(function($municipio) {
            return [
                'id' => $municipio->id,
                'nombre' => $municipio->nombre,
                'codigo' => $municipio->codigo,
                'estado_id' => $municipio->estado_id,
                'nombre_estado' => $municipio->estado->nombre ?? null,  // Nombre del estado
            ];
        });

    return response()->json($municipios, 200);
}


public function create(Request $request)
{
    $fields = $request->validate([
        'nombre' => 'required|string',
        'codigo' => 'required|string',
        'estado_id' => 'required|integer',
    ]);

    $municipio = Municipio::create([
        'nombre' => $fields['nombre'],
        'codigo' => $fields['codigo'],
        'estado_id' => $fields['estado_id'],
    ]);

    return response()->json($municipio, 201);
}


public function update(Request $request, Municipio $municipio)
{
    $fields = $request->validate([
        'nombre' => 'required|string',
        'codigo' => 'required|string',
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

// se agrega la función getCiudades para obtener las ciudades de un municipio

public function getCiudades(Municipio $municipio)
{
    if (!$municipio) {
        return response()->json(['message' => 'Municipio no encontrado'], 404);
    }
    $ciudades = $municipio->ciudades; // se asignan las ciudades a la variable $ciudades y se retornan en formato json
    return response()->json($ciudades, 200);
}
}