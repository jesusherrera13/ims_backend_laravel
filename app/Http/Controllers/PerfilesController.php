<?php

namespace App\Http\Controllers;

use App\Models\Perfiles;
use Illuminate\Http\Request;

class PerfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Perfiles::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        $fields = $request->validate([
            'apellido' => 'required|string' ,
            'nombre' => 'required|string',
        ]);

        $perfiles = Perfiles::create([
            'apellido' => $fields['apellido'],
            'nombre' => $fields['nombre'],
            
        ]);

        $response = [
            'perfiles' => $perfiles,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfiles $perfiles)
    {
        return response()->json($perfiles, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perfiles $Perfiles)
    {
        $fields = $request->validate([
            'apellido' => 'required|string',
            'nombre' => 'required|string',
            // 'password' => 'required|string|confirmed',
        ]);

        $Perfiles->update($fields);

        $response = [
            'Perfiles' => $Perfiles,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfiles $perfiles)
    {
        $perfiles->delete();
        return response()->json(['message' => 'Perfil eliminado'], 200);
    }
}
