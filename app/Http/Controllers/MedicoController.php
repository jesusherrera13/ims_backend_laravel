<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function index()
{
    $medicos = Medico::with(['ciudad', 'estado', 'especialidad'])->get();

    $response = $medicos->map(function($medico) {
        return [
            'id' => $medico->id,
            'nombre' => $medico->nombre,
            'rfc' => $medico->rfc,
            'direccion' => $medico->direccion,
            'cp' => $medico->cp,
            'genero' => $medico->genero,
            'nombre_ciudad' => $medico->ciudad ? $medico->ciudad->nombre : null,
            'nombre_estado' => $medico->estado ? $medico->estado->nombre : null,
            'nombre_especialidad' => $medico->especialidad ? $medico->especialidad->nombre : null,
        ];
    });

    return response()->json($response, 200);
}



    public function create(Request $request)
    {

       
        $fields = $request->validate([
            'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|max:13|unique:medicos,rfc',
            'direccion' => 'required|string',
            'cp' => 'required|string|max:10',
            'ciudad_id' => 'nullable|exists:system_ciudades,id',
            'estado_id' => 'nullable|exists:system_estados,id',
            'genero' => 'required|string|in:masculino,femenino,otro',
            'especialidad_id' => 'required|exists:system_especialidades_medicas,id',
        ]);

        $medico = Medico::create($fields);

        return response()->json($medico, 201);
    }

    public function update(Request $request, Medico $medico)
    {
        $fields = $request->validate([
            'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|max:13|unique:medicos,rfc,' . $medico->id,
            'direccion' => 'required|string',
            'cp' => 'required|string|max:10',
            'ciudad_id' => 'nullable|exists:system_ciudades,id',
            'estado_id' => 'nullable|exists:system_estados,id',
            'genero' => 'required|string|in:masculino,femenino,otro',
            'especialidad_id' => 'required|exists:system_especialidades_medicas,id',
        ]);

        $medico->update($fields);

        return response()->json($medico, 200);
    }

    public function destroy(Medico $medico)
    {
        $medico->delete();
        return response()->json(['message' => 'Médico eliminado'], 200);
    }
}