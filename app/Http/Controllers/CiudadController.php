<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function index()
    {
        return response()->json(Ciudad::all(), 200);
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