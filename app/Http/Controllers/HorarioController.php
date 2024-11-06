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
    public function index()
    {
        $horarios = Horario::all(); //sirve para traer todos los registros de la tabla
        return response()->json($horarios, 200); //retorna un json con los registros
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
        ]);

        return response()->json($horario, 201);
    }

    public function updateHorario(Request $request, Horario $horario)
    {
        $fields = $request->validate([
            'start_time' => ['required', new TimeValidation()],
            'end_time' => ['required', new TimeValidation()],
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
