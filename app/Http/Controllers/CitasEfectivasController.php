<?php

namespace App\Http\Controllers;

use App\Models\citasEfectivas;
use Illuminate\Http\Request;

class CitasEfectivasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(citasEfectivas::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        $fields = $request->validate([
            'paciente' => 'required|string' ,
            'especialidadM' => 'required|string',
            'doctor' => 'required|string',
            'fecha' => 'required|date',
            'efectividad' => 'required|string',

        ]);

        $citaE = citasEfectivas::create([
            'paciente' => $fields['paciente'],
            'especialidadM' => $fields['especialidadM'],
            'doctor' => $fields['doctor'],
            'fecha' => $fields['fecha'],
            'efectividad' => $fields['efectividad'],
            
        ]);

        $response = [
            'citasE' => $citaE,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(citasEfectivas $citaE)
    {
        return response()->json($citaE, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, citasEfectivas $citasE)
    {
        $fields = $request->validate([
            'paciente' => 'required|string' ,
            'especialidadM' => 'required|string',
            'doctor' => 'required|string',
            'fecha' => 'required|date',
            'efectividad' => 'required|string',

        ]);

        $citasE = citasEfectivas::create([
            'paciente' => $fields['paciente'],
            'especialidadM' => $fields['especialidadM'],
            'doctor' => $fields['doctor'],
            'fecha' => $fields['fecha'],
            'efectividad' => $fields['efectividad'],
            
        ]);

        $response = [
            'citasE' => $citasE,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(citasEfectivas $citasE)
    {
        $citasE->delete();
        return response()->json(['message' => 'cita eliminada'], 200);
    }
}
