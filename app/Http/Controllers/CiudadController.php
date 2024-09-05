<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function index()
{
    $ciudades = Ciudad::with('municipio')
        ->select('id', 'nombre', 'municipio_id')
        ->get()
        ->map(function($ciudad) {
            return [
                'id' => $ciudad->id,
                'nombre' => $ciudad->nombre,
                'municipio_id' => $ciudad->municipio_id,
                'nombre_municipio' => $ciudad->municipio->nombre ?? null,  // Nombre del municipio
            ];
        });

    return response()->json($ciudades, 200);
}



    public function create(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'municipio_id' => 'required|integer',
        ]);

        $ciudad = Ciudad::create([
            'nombre' => $fields['nombre'],
            'municipio_id' => $fields['municipio_id'],
        ]);

        return response()->json($ciudad, 201);
    }


    public function update(Request $request, Ciudad $ciudad)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'municipio_id' => 'required|integer',
        ]);

        $ciudad->update($fields);

        return response()->json($ciudad, 200);
    }

    public function destroy(Ciudad $ciudad)
    {
        $ciudad->delete();
        return response()->json(['message' => 'Ciudad eliminado'], 200);
    }
}