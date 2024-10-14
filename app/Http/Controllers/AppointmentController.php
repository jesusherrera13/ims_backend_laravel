<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // si se quiere ver los datos de los pacientes y doctores en la tabla de citas
    public function index()
    {
        $appointments = Appointment::with('patient', 'doctor')
            ->select(
                'id',
                'patient_id',
                'doctor_id',
                'date',
                'hour'
            )
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'date' => $appointment->date,
                    'hour' => $appointment->hour,
                    'patient' => $appointment->patient->nombre ?? null,
                    'doctor' => $appointment->doctor->nombre ?? null,
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
            'doctor_id' => 'required|integer',
            'date' => 'required|date',
            'hour' => 'required|time',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $fields['patient_id'],
            'doctor_id' => $fields['doctor_id'],
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
            'doctor_id' => 'required|integer',
            'date' => 'required|date',
            'hour' => 'required|time',      
    ]);}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json('message: "Cita eliminada"', 200);
    }
}
