<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use Illuminate\Http\Request;

class PaisController extends Controller
{
    public function index()
    {
        return response()->json(Pais::all(), 200);
    }

    public function create(Request $request)
    {

        $fields = $request->validate([

            'nombre' => 'required|string',
        ]);

        $pais = Pais::create([
            'nombre' => $fields['nombre'],
        ]);

        return response()->json($pais, 201);
    }

    public function update(Request $request, Pais $pais)
    {

        $fields = $request->validate([
            'nombre' => 'required|string'
        ]);

        $pais->update($fields);

        return response()->json($pais, 200);
    }

    public function destroy(Pais $pais)
    {
        $pais->delete();
        return response()->json(['message' => 'Pais eliminado'], 200);

    }

}