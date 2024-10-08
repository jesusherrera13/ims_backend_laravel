<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Illuminate\Http\Request;
use App\Http\Requests\CompaniaCreateRequest;
use App\Models\Empresa;

class CompaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Compania::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompaniaCreateRequest $request)
    {
        $validated = $request->validated();
        $compania = Compania::create($validated);

        return response()->json($compania, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Compania $compania)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compania $compania)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compania $compania)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compania $compania)
    {
        //
    }

    public function getEmpresas(Empresa $empresa ) // se recibe un objeto de tipo Pais como parámetro  
    {
        if (!$empresa) {
            return response()->json(['error' => 'empresa no encontrado'], 404);
        }
    
        $empresas = $empresa->empresas; // se asignan los estados a la variable $empresas y se retornan en formato json 
        return response()->json($empresas, 200);
    }
}
