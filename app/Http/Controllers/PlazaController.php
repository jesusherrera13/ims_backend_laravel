<?php

namespace App\Http\Controllers;

use App\Models\Plaza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlazaCreateRequest;
use App\Http\Requests\PlazaUpdateRequest;
use App\Http\Resources\PlazaResource;

class PlazaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Plaza::select("plazas.id","plazas.nombre","empresas.razon_social")
                    ->join("empresas","empresas.id","plazas.company_id")
                    ->get()
            ,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request) {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'created_by' => 'nullable|integer',
            'company_id' => 'required|integer'
      

        ]);

        $plaza = Plaza::create([
            'nombre' => $fields['nombre'],
            'created_by' => $fields['created_by'],
            'company_id' => $fields['company_id']
        
        
        ]);

        $response = [
            'plaza' => $plaza,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Plaza $plaza)
    {
        return response()->json($plaza, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plaza $plaza)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'created_by' => 'nullable|integer',
            'company_id' => 'required|integer'
     
            
        ]);

        $plaza->update($fields);

        $response = [
            'plaza' => $plaza,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plaza $plaza)
    {
        $plaza->delete();
        return response()->json(['message'=>'Plaza eliminada'],200);
        //
    }




    public function syncReproceso(Request $request) {

        $plazas = DB::table("employees")
                        ->select("base_location_id","base_location_name","business_name_id","business_name","company_id","company_name")
                        ->groupBy("base_location_id")
                        ->get();

        foreach($plazas as $row) {
            $plaza = Plaza::where('base_location_id', $row->base_location_id)->where('business_name_id', $row->business_name_id)->where('company_id', $row->company_id)->first();

            if($plaza && $plaza->count()) {
                $rick = new PlazaUpdateRequest();
                $rick->replace([
                    'nombre' => $row->base_location_name,
                    'base_location_id' => $row->base_location_id,
                    'business_name_id' => $row->business_name_id,
                    'company_id' => $row->company_id,
                ]);

                $plaza->update($rick->all());
            }
            else {
                $rick = new PlazaCreateRequest();
                $rick->replace([
                    'nombre' => $row->base_location_name,
                    'base_location_id' => $row->base_location_id,
                    'business_name_id' => $row->business_name_id,
                    'company_id' => $row->company_id,
                ]);

                Plaza::create($rick->all());
            }
        }

        return response()->json(PlazaResource::collection(Plaza::all()), 200);
    }

    public function accesoPlazas(Request $request) {

        $data = [];

        $query = DB::table("company")
                    ->select("id","company_id","company_name")
                    ->orderBy("company_name");

        $tmp = $query->get();

        foreach($tmp as $k => $row) {
            $row->company_name = ucwords(strtolower($row->company_name));
            if(!isset($row->empresas)) $row->empresas = [];

            $query = DB::table("business_names")
                        ->select("id","business_name_id","business_name")
                        ->where("company_id", $row->company_id)
                        ->orderBy("business_name");
            
            $tmp2 = $query->get();

            foreach($tmp2 as $k2 => $row2) {
                $row2->business_name = ucwords(strtolower($row2->business_name));
                if(!isset($row2->plazas)) $row2->plazas = [];

                $query = DB::table("plazas")
                        ->select("plazas.id","plazas.nombre","plazas.base_location_id","plazas.business_name_id")
                        ->where("business_name_id", $row2->business_name_id)
                        ->orderBy("nombre");

                $tmp3 = $query->get();
    
                foreach($tmp3 as $k3 => $row3) {
                    $row3->nombre = ucwords(strtolower($row3->nombre));
                    $row2->plazas[] = $row3;
                }

                $row->empresas[] = $row2;
            }

            $data[] = $row;
        }

        return response()->json($data, 200);
    }
}
