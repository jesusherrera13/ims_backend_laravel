<?php

namespace App\Http\Controllers;

use App\Models\MetodosP;
use Illuminate\Http\Request;

class MetodosPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(MetodosP::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
        ]);

        $metodosP = MetodosP::create([
            'nombre' => $fields['nombre'],
            
        ]);

        $response = [
            'metodosP' => $metodosP,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MetodosP $metodosP)
    {
        return response()->json($metodosP, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MetodosP $metodoPagos)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
        ]);

        $metodoPagos->update($fields);

        $response = [
            'metodoPagos' => $metodoPagos,
        ];

        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MetodosP $metodoPagos)
    {
        {
            $metodoPagos->delete();
            return response()->json(['message' => 'Metodo de pago eliminado'], 200);
        }
    }
}
