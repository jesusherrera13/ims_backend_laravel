<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function index()
    {
        $query = DB::table("pacientes as paciente")
            ->leftJoin("system_religiones as religion", "religion.id", "religion_id")
            ->select(
                "paciente.id",
                "paciente.nombre",
                "paciente.apellido1",
                "paciente.apellido2",
                "religion.nombre as nombre_religion",
                "paciente.f_nacimiento",
                "paciente.domicilio",
                "paciente.foto_perfil"
            );

        $response = $query->get();

        return response()->json($response, 200);
    }

    public function create(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'apellido1' => 'required|string',
            'apellido2' => 'nullable|string',
            'religion_id' => 'nullable|integer',
            'f_nacimiento' => 'nullable|date',
            'domicilio' => 'nullable|string',
            'foto_perfil' => 'nullable|string',
        ]);

        $paciente = Paciente::create([
            'nombre' => $fields['nombre'],
            'apellido1' => $fields['apellido1'],
            'apellido2' => $fields['apellido2'],
            'religion_id' => $fields['religion_id'],
            'f_nacimiento' => $fields['f_nacimiento'],
            'domicilio' => $fields['domicilio'],
            'foto_perfil' => $fields['foto_perfil'],
        ]);

        return response()->json($paciente, 201);
    }


    public function update(Request $request, Paciente $paciente)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'apellido1' => 'required|string',
            'apellido2' => 'nullable|string',
            'religion_id' => 'nullable|integer',
            'f_nacimiento' => 'nullable|date',
            'domicilio' => 'nullable|string',
            'foto_perfil' => 'nullable|string',
        ]);

        $paciente->update($fields);

        return response()->json($paciente, 200);
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return response()->json(['message' => 'Paciente eliminado'], 200);
    }
}