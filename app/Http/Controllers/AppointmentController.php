<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Medico;
use App\Models\Horario;
use App\Models\EspecialidadMedica;
use App\Rules\TimeValidation;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // si se quiere ver los datos de los pacientes y doctores en la tabla de citas
    public function index()
    {
        $appointments = Appointment::with('patient', 'medico', 'especialidad')
            ->select(
                'id',
                'patient_id',
                'medico_id',
                'especialidad_id',
                'date',
                'hour'
            )
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_id' => $appointment->patient_id,
                    'medico_id' => $appointment->medico_id,
                    'especialidad_id' => $appointment->especialidad_id,
                    'date' => $appointment->date,
                   'hour' => \Carbon\Carbon::createFromFormat('H:i:s', $appointment->hour)->format('H:i'),
                    'especialidad_name' => $appointment->especialidad->nombre ?? null,
                    'patient_name' => $appointment->patient->nombre ?? null,
                    'patient_lastname' => $appointment->patient->apellido1 ?? null,
                    'medico_name' => $appointment->medico->nombre ?? null,
                ];
            });
            return response()->json($appointments, 200);
        }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         $fields = $request->validate([
            'patient_id' => 'required|integer',
            'medico_id' => 'required|integer',
            'especialidad_id' => 'required|integer',
            'date' => 'required|date',
            'hour' => ['required', new TimeValidation()],
        ]);

        $appointment = Appointment::create([
            'patient_id' => $fields['patient_id'],
            'medico_id' => $fields['medico_id'],
            'especialidad_id' => $fields['especialidad_id'],
            'date' => $fields['date'],
            'hour' => $fields['hour'],
        ]);

        return response()->json($appointment, 201);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
         $fields = $request->validate([
            'patient_id' => 'required|integer',
            'medico_id' => 'required|integer',
            'especialidad_id' => 'required|integer',
            'date' => 'required|date',
            'hour' => ['required', new TimeValidation()],
            
    ]);
     $appointment->update($fields);

    return response()->json($appointment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json('message: "Cita eliminada"', 200);
    }

    public function getEspecialidadesMedicas(Medico $medico) // se recibe un objeto de tipo Medico como parámetro  
    {
        if (!$medico) { // si no se encuentra el medico se retorna un mensaje de error
            return response()->json(['error' => 'medico no encontrado'], 404);
        }
    
        $especialidadesMedicas = $medico->especialidadesMedicas; // se asignan las especialidades a la variable $especialidadesMedicas y se retornan en formato json 
        return response()->json($especialidadesMedicas, 200);
    }

    public function associateEspecialidadMedica(Request $request, Medico $medico)
    {
        $fields = $request->validate([
            'especialidad_id' => 'required|integer|exists:especialidad_medicas,id',
        ]);

        $especialidad = EspecialidadMedica::find($fields['especialidad_id']);
        if (!$especialidad) {
            return response()->json(['error' => 'especialidad no encontrada'], 404);
        }

        $medico->especialidadesMedicas()->attach($especialidad);

        return response()->json(['message' => 'Especialidad asociada correctamente'], 200);
    }

 /*    public function getHorarios(Medico $medico, EspecialidadMedica $especialidad) // se recibe un objeto de tipo Pais como parámetro  
    {
        $especialidadMedica = $medico->especialidadesMedicas()->where('id', $especialidad->id)->first();
        if (!$especialidadMedica) {
            return response()->json(['error' => 'especialidad no encontrada para el medico'], 404);
        }
    
        $horarios = $medico->horarios; // se asignan los horarios a la variable $horarios y se retornan en formato json
        return response()->json($horarios, 200);
    } */

    public function getCitas (Medico $medico, EspecialidadMedica $especialidad)
    {
        $especialidadMedica = $medico->especialidadesMedicas()->where('id', $especialidad->id)->first();
        if (!$especialidadMedica) {
            return response()->json(['error' => 'especialidad no encontrada para el medico'], 404);
        }
    
        $citas = $medico->citas; // se asignan los horarios a la variable $horarios y se retornan en formato json
        return response()->json($citas, 200);
    }

    public function getCita(Medico $medico, EspecialidadMedica $especialidad, Appointment $cita)
    {
        $especialidadMedica = $medico->especialidadesMedicas()->where('id', $especialidad->id)->first();
        if (!$especialidadMedica) {
            return response()->json(['error' => 'especialidad no encontrada para el medico'], 404);
        }
    
        $cita = $medico->citas()->where('id', $cita->id)->first();
        if (!$cita) {
            return response()->json(['error' => 'cita no encontrada para el medico'], 404);
        }
    
        return response()->json($cita, 200);
    }

    public function createCita(Request $request, Medico $medico, EspecialidadMedica $especialidad)
    {
        $fields = $request->validate([
            'patient_id' => 'required|integer',
            'date' => 'required|date',
            'hour' => ['required', new TimeValidation()],
        ]);

        $cita = Appointment::create([
            'patient_id' => $fields['patient_id'],
            'medico_id' => $medico->id,
            'especialidad_id' => $especialidad->id,
            'date' => $fields['date'],
            'hour' => $fields['hour'],
        ]);

        return response()->json($cita, 201);
    }

    public function updateCita(Request $request, Medico $medico, EspecialidadMedica $especialidad, Appointment $cita)
    {
        $fields = $request->validate([
            'patient_id' => 'required|integer',
            'date' => 'required|date',
            'hour' => ['required', new TimeValidation()],
        ]);

        $cita->update([
            'patient_id' => $fields['patient_id'],
            'date' => $fields['date'],
            'hour' => $fields['hour'],
        ]);

        return response()->json($cita, 200);
    }

    public function destroyCita(Medico $medico, EspecialidadMedica $especialidad, Appointment $cita)
    {
        $cita->delete();
        return response()->json('message: "Cita eliminada"', 200);
    }

}