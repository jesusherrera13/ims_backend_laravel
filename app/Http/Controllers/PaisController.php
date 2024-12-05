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
            'codigo_iso_alfa2' => 'required|string',
            'codigo_iso_alfa3' => 'required|string',
            'codigo_iso_numerico' => 'required|integer'

            
        ]);

        $pais = Pais::create([
            'nombre' => $fields['nombre'],
            'codigo_iso_alfa2' => $fields['codigo_iso_alfa2'],
            'codigo_iso_alfa3' => $fields['codigo_iso_alfa3'],
            'codigo_iso_numerico' => $fields['codigo_iso_numerico']
        ]);

        return response()->json($pais, 201);
    }

    public function update(Request $request, Pais $pais)
    {

        $fields = $request->validate([
            'nombre' => 'required|string',
            'codigo_iso_alfa2' => 'required|string',
            'codigo_iso_alfa3' => 'required|string',
            'codigo_iso_numerico' => 'required|integer'

            
        ]);

        $pais->update($fields);

        return response()->json($pais, 200);
    }

    public function destroy(Pais $pais)
    {
        $pais->delete();
        return response()->json(['message' => 'Pais eliminado'], 200);

    }

    // se agrega la función getEstados para obtener los estados de un país
    
    public function getEstados(Pais $pais ) // se recibe un objeto de tipo Pais como parámetro  
    {
        if (!$pais) {
            return response()->json(['error' => 'País no encontrado'], 404);
        }
    
        $estados = $pais->estados; // se asignan los estados a la variable $estados y se retornan en formato json 
        return response()->json($estados, 200);
    }

    

}