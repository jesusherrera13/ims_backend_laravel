<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Equipo::select(
                "equipos.id","equipos.nombre","equipos.departamento_id","departamentos.department_name as departamento"
                )
                ->join("departamentos","departamentos.id","equipos.departamento_id")
                ->get()
            ,200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'departamento_id' => 'required',
            'nombre' => 'required|string',
        ]);

        $equipo = Equipo::create([
            'departamento_id' => $fields['departamento_id'],
            'nombre' => $fields['nombre'],
        ]);

        return response()->json($equipo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipo $equipo)
    {
        return response()->json($equipo, 200);
        // return response()->json(Equipo::with('actividades')->find($equipo->id), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipo $equipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipo $equipo)
    {
        $fields = $request->validate([
            'departamento_id' => 'required',
            'nombre' => 'required|string',
        ]);
        
        $equipo->update($fields);

        return response()->json($equipo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return response()->json([
            "message" => "Registro eliminado"
        ], 200);
    }
}
