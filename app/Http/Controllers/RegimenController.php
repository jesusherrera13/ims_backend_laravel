<?php

namespace App\Http\Controllers;

use App\Models\Regimen;
use Illuminate\Http\Request;

class RegimenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Regimen::all(), 200);
    }

    public function create(Request $request) {
        $fields = $request->validate([
            'clave' => 'required|string' ,
            'nombre' => 'required|string',
        ]);

        $regimen = Regimen::create([
            'clave' => $fields['clave'],
            'nombre' => $fields['nombre'],
            
        ]);

        $response = [
            'regimen' => $regimen,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    public function show(Regimen $regimen)
    {
        return response()->json($regimen, 200);
    }

    public function update(Request $request, Regimen $Regimen)
    {
        $fields = $request->validate([
            'clave' => 'required|string',
            'nombre' => 'required|string',
            // 'password' => 'required|string|confirmed',
        ]);

        $Regimen->update($fields);

        $response = [
            'Regimen' => $Regimen,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    public function destroy(Regimen $regimen)
    {
        $regimen->delete();
        return response()->json(['message' => 'Regimen eliminado'], 200);
    }
}
