<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;


class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Empresa::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request) {
        $fields = $request->validate([
            'razon_social' => 'required|string',
            'nombre_comercial' => 'required|string',
            'rfc' => 'required|string',
        'pais_id' => 'nullable|integer|exists:paises,id',
            'estado_id' => 'nullable|integer',
            'municipio_id' => 'nullable|integer',
            'codigo_postal' => 'nullable|integer', 
            'ciudad_id' => 'nullable|integer',
            'colonia_id' => 'nullable|integer',
            'regimen_id' => 'nullable|integer',
            'calle' => 'nullable|string',
            'numero_exterior' => 'nullable|string',
            'numero_interior' => 'nullable|string',
            'registro_patronal' => 'nullable|string',
        ]);

        $empresa = Empresa::create([
            'razon_social' => $fields['razon_social'],
            'nombre_comercial' => $fields['nombre_comercial'],
            'rfc' => $fields['rfc'],
            'pais_id' => isset($fields['pais_id']) ? $fields['pais_id'] : null,
            'estado_id' => $fields['estado_id'] ?? null,
            'municipio_id' => $fields['municipio_id'] ?? null,
            'ciudad_id' => $fields['ciudad_id'] ?? null,
            'colonia_id' => $fields['colonia_id'] ?? null,
            'codigo_postal' => $fields['codigo_postal'] ?? null,
            'calle' => $fields['calle'] ?? null,
            'numero_exterior' => $fields['numero_exterior'] ?? null,
            'numero_interior' => $fields['numero_interior'] ?? null,
            'registro_patronal' => $fields['registro_patronal'] ?? null,   
             'regimen_id' => $fields['regimen_id'] ?? null,
           

        
        ]);

        $response = [
            'empresa' => $empresa,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        return response()->json($empresa, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $fields = $request->validate([
            'razon_social' => 'required|string',
            'nombre_comercial' => 'required|string',
            'rfc' => 'required|string',
            'pais_id' => 'nullable|integer',
            'estado_id' => 'nullable|integer',
            'municipio_id' => 'nullable|integer',
            'ciudad_id' => 'nullable|integer',
            'colonia_id' => 'nullable|integer',
            'codigo_postal' => 'nullable|integer',
            'calle' => 'nullable|string',
            'numero_exterior' => 'nullable|string',
            'numero_interior' => 'nullable|string',
            'registro_patronal' => 'nullable|string',
            'regimen_id' => 'nullable|integer',
    
            
            
        ]);

        $empresa->update($fields);

        $response = [
            'empresa' => $empresa,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
      /*   $empresa->delete();
        return response()->json(['message'=>'Empresa eliminada'],200);
        // */
        try {
            DB::beginTransaction();

            // Eliminar las relaciones antes de eliminar la empresa
            // Por ejemplo, eliminar las plazas asociadas
            if ($empresa->plazas()->exists()) {
                $empresa->plazas()->delete(); // Eliminar las plazas asociadas
            }

            // Ahora eliminar la empresa misma
            $empresa->delete();

            DB::commit();

            return response()->json(['message' => 'Empresa eliminada correctamente'], 200);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(['error' => 'Error al eliminar empresa: ' . $e->getMessage()], 500);
        }
    }
    }

