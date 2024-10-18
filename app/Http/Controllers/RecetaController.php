<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function index()
{
    $recetas = Receta::with(['paciente', 'especialidad'])
        ->select('id_receta', 'paciente_id', 'especialidad_id', 'descrip', 'fecha', 'sta')
        ->get()
        ->map(function($receta) {
            return [
                'id_receta' => $receta->id_receta,
                'paciente_id' => $receta->paciente_id,
                'nombre_paciente' => $receta->paciente->nombre ?? null,  // Nombre del paciente
                'especialidad_id' => $receta->especialidad_id,
                'nombre_especialidad' => $receta->especialidad->nombre ?? null,  // Nombre de la especialidad
                'descrip' => $receta->descrip,
                'fecha' => $receta->fecha,
                'sta' => $receta->sta,
            ];
        });

    return response()->json($recetas, 200);
}


public function create(Request $request)
{
    $fields = $request->validate([
        'paciente_id' => 'required|integer|exists:pacientes,id',
        'especialidad_id' => 'required|integer|exists:especialidades,id',
        'descrip' => 'required|string',
        'fecha' => 'required|date',
        'sta' => 'required|char:1',
    ]);

    $receta = Receta::create([
        'paciente_id' => $fields['paciente_id'],
        'especialidad_id' => $fields['especialidad_id'],
        'descrip' => $fields['descrip'],
        'fecha' => $fields['fecha'],
        'sta' => $fields['sta'],
    ]);

    return response()->json($receta, 201);
}

public function update(Request $request, Receta $receta)
{
    $fields = $request->validate([
        'paciente_id' => 'required|integer|exists:pacientes,id',
        'especialidad_id' => 'required|integer|exists:especialidades,id',
        'descrip' => 'required|string',
        'fecha' => 'required|date',
        'sta' => 'required|char:1',
    ]);

    $receta->update($fields);

    return response()->json($receta, 200);
}

public function destroy(Receta $receta)
{
    $receta->delete();
    return response()->json(['message' => 'Receta eliminada'], 200);
}

}