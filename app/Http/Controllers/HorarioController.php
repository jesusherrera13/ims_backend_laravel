<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\EspecialidadMedica;
use App\Rules\TimeValidation;


class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $horarios = Horario::all(); //sirve para traer todos los registros de la tabla
    //     return response()->json($horarios, 200); //retorna un json con los registros
    // }
    public function index()
    {
        $horarios = Horario::with('medico', 'especialidad')
        ->select(
            'id',
            'medico_id',
            'especialidad_id',
            'start_time',
            'end_time'
        )
        ->get()
            ->map(function ($horario) {
                return [
                    'id' => $horario->id,
                    'medico_id' => $horario->medico_id,
                    'especialidad_id' => $horario->especialidad_id,
                    'start_time' => \Carbon\Carbon::createFromFormat('H:i:s', $horario->start_time)->format('H:i'),
                    'end_time' => \Carbon\Carbon::createFromFormat('H:i:s', $horario->end_time)->format('H:i'),
                    'especialidad_name' => $horario->especialidad->nombre ?? null,
                    'medico_name' => $horario->medico->nombre ?? null,
                ];
            });

        return response()->json($horarios, 200);
    }
    public function create(Request $request)
    {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:system_especialidades_medicas,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'intervalo' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        // Crear un nuevo horario
        $horario = Horario::create($validatedData);

        // Retornar una respuesta adecuada
        return response()->json($horario, 201);
    }

    public function update(Request $request, Horario $horario)
    {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'intervalo' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        // Actualizar el horario
        $horario->update($validatedData);

        // Retornar una respuesta adecuada
        return response()->json($horario, 200);
    }

    public function destroy(Horario $horario)
    {
        // Eliminar el horario
        $horario->delete();

        // Retornar una respuesta adecuada
        return response()->json(['message' => 'Horario eliminado'], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
/*     public function getHorarios(Medico $medico, EspecialidadMedica $especialidad)
    {
        $especialidadMedica = $medico->especialidadesMedicas()->where('id', $especialidad->id)->first();
        if (!$especialidadMedica) {
            return response()->json(['error' => 'especialidad no encontrada para el medico'], 404);
        }
    
        $horarios = $medico->horarios; // se asignan los horarios a la variable $horarios y se retornan en formato json
        return response()->json($horarios, 200);
    } */

    public function getHorarios(Medico $medico, EspecialidadMedica $especialidad)
    {
        // Verificar si el médico tiene la especialidad específica
        $especialidadMedica = $medico->especialidadesMedicas()->where('id', $especialidad->id)->first();
        if (!$especialidadMedica) {
            return response()->json(['error' => 'especialidad no encontrada para el medico'], 404);
        }

        // Obtener los horarios del médico filtrados por la especialidad
        $horarios = $medico->horarios()->where('especialidad_id', $especialidad->id)->get();

        return response()->json($horarios, 200);
    }


    public function createHorario(Request $request, Medico $medico, EspecialidadMedica $especialidad)
    {
        $fields = $request->validate([
            'start_time' => ['required', new TimeValidation()],
            'end_time' => ['required', new TimeValidation()],
        ]);

        $horario = Horario::create([
            'medico_id' => $medico->id,
            'especialidad_id' => $especialidad->id,
            'start_time' => $fields['start_time'],
            'end_time' => $fields['end_time'],
            'intervalo' => 30, // intervalo por defecto
            'active' => true, // activo por defecto
        ]);

        return response()->json($horario, 201);
    }

    public function updateHorario(Request $request, Horario $horario)
    {
        $fields = $request->validate([
            'start_time' => ['required', new TimeValidation()],
            'end_time' => ['required', new TimeValidation()],
            'intervalo' => ['required', 'integer'], // intervalo de la cita en minutos
            'active' => ['required', 'boolean'], // activo o inactivo por si esta de vacaciones
        ]);

        $horario->update($fields);

        return response()->json($horario, 200);
    }

    public function destroyHorario(Horario $horario)
    {
        $horario->delete();
        return response()->json(['message' => 'Horario eliminado'], 200);
    }
}
