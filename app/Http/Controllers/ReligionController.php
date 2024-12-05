<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReligionController extends Controller
{
    public function index()
    {
        $query = DB::table("system_religiones")
            ->select("id", "nombre")
            ->orderBy('nombre', 'asc'); // Ordenar alfabéticamente por el nombre

        $response = $query->get();

        return response()->json($response, 200);
    }


    public function create(Request $request)
    {

        $fields = $request->validate([

            'nombre' => 'required|string'

            
        ]);

        $religion = Religion::create([
            'nombre' => $fields['nombre']
        ]);

        return response()->json($religion, 201);
    }

    public function update(Request $request, Religion $religion)
    {

        $fields = $request->validate([
            'nombre' => 'required|string'
        ]);

        $religion->update($fields);

        return response()->json($religion, 200);
    }

    public function destroy(Religion $religion)
    {
        $religion->delete();
        return response()->json(['message' => 'Religion eliminada'], 200);

    }

    // se agrega la función getEstados para obtener los estados de un país
    
    public function getReligiones(Religion $religion ) // se recibe un objeto de tipo Religion como parámetro  
    {
        if (!$religion) {
            return response()->json(['error' => 'Religión no encontrada'], 404);
        }
    
        $religiones = $religion->estados; // se asignan los estados a la variable $estados y se retornan en formato json 
        return response()->json($religiones, 200);
    }
}