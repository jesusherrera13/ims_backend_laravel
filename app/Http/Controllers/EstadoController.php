<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function index()
{
    $estados = Estado::with('pais')
        ->select('id', 'nombre', 'codigo', 'abreviacion', 'pais_id')
        ->get()
        ->map(function($estado) {
            return [
                'id' => $estado->id,
                'nombre' => $estado->nombre,
                'codigo' => $estado->codigo,
                'abreviacion' => $estado->abreviacion,
                'pais_id' => $estado->pais_id,
                'nombre_pais' => $estado->pais->nombre ?? null,
            ];
        });

    return response()->json($estados, 200);
}



public function create(Request $request)
{
    $fields = $request->validate([
        'nombre' => 'required|string',
        'codigo' => 'required|string',
        'abreviacion' => 'required|string',
        'pais_id' => 'required|integer',
    ]);

    $estado = Estado::create([
        'nombre' => $fields['nombre'],
        'codigo' => $fields['codigo'],
        'abreviacion' => $fields['abreviacion'],
        'pais_id' => $fields['pais_id'],
    ]);

    return response()->json($estado, 201);
}


public function update(Request $request, Estado $estado)
{
    $fields = $request->validate([
        'nombre' => 'required|string',
        'codigo' => 'required|string',
        'abreviacion' => 'required|string',
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

// se agrega la función getMunicipios para obtener los municipios de un estado

public function getMunicipios(Estado $estado)
{

    // Verificar si se encontró el estado
    if (!$estado) {
        return response()->json(['message' => 'Estado no encontrado'], 404);
    }

    // si se en encontro el estado se asignan los municipios a la variable $municipios
    $municipios = $estado->municipios;

    // Devolver los municipios como respuesta JSON
    return response()->json($municipios, 200);
}
}