<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Illuminate\Http\Request;
use App\Http\Requests\CompaniaCreateRequest;

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
}
