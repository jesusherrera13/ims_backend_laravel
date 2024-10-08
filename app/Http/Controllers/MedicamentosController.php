<?php

namespace App\Http\Controllers;

use App\Models\Medicamentos;
use Illuminate\Http\Request;

class MedicamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Medicamentos::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string' ,
            'descripcion' => 'required|string',
            'cantidad' => 'required|string',
            'presentacion' => 'required|string',
            'indicaciones' => 'required|string',
            'dosis' => 'required|string',

        ]);

        $medicamento = Medicamentos::create([
            'nombre' => $fields['nombre'],
            'descripcion' => $fields['descripcion'],
            'cantidad' => $fields['cantidad'],
            'presentacion' => $fields['presentacion'],
            'indicaciones' => $fields['indicaciones'],
            'dosis' => $fields['dosis'],
            
        ]);

        $response = [
            'medicamento' => $medicamento,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicamentos $medicamento)
    {
        return response()->json($medicamento, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medicamentos $Medicamentos)
    {
        $fields = $request->validate([
            'nombre' => 'required|string' ,
            'descripcion' => 'required|string',
            'cantidad' => 'required|string',
            'presentacion' => 'required|string',
            'indicaciones' => 'required|string',
            'dosis' => 'required|string',

        ]);

        $Medicamentos->update($fields);

        $response = [
            'Medicamentos' => $Medicamentos,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicamentos $medicamentos)
    {
        $medicamentos->delete();
        return response()->json(['message' => 'Medicamento eliminado'], 200);
    }
}
