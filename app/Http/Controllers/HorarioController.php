<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

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
    public function create( Request $request)
    {
        $fields = $request->validate([
        'start_time' => 'required|date',
        'end_time' => 'required|date',
    ]);

    $horario = Horario::create($fields);

    return response()->json($horario, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $fields = $request->validate([
            'start_time' => 'required|date' ,
            'end_time' => 'required|date',
            

        ]);

        $horario->update($fields);


    
    return response()->json($response, 200);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();
        return response()->json(['message' => 'Horario eliminado'], 200);
    }
}
