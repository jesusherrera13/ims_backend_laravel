<?php

namespace App\Http\Controllers;

use App\Models\EspecialidadMedica;
use App\Models\Medico;
use Illuminate\Http\Request;

class EspecialidadMedicaController extends Controller
{
    public function index()
    {
        return response()->json(EspecialidadMedica::all(), 200);
    }

    public function create(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $especialidad = EspecialidadMedica::create($fields);

        return response()->json($especialidad, 201);
    }

    public function update(Request $request, EspecialidadMedica $especialidad)
    {
        $fields = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $especialidad->update($fields);

        return response()->json($especialidad, 200);
    }

/*     public function destroy(EspecialidadMedica $especialidad)
    {
        $especialidad->delete();
        return response()->json(['message' => 'Especialidad eliminada'], 200);
    } */
}