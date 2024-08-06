<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Modulo::all(), 200);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Modulo $modulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modulo $modulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modulo $modulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modulo $modulo)
    {
        //
    }

    public function accesoModulos(Request $request) {

        $data = [];

        $query = DB::table("system_modulos")
                    ->select("id","nombre","route","icon","orden","parent_id",DB::raw("1 as nivel"),DB::raw("concat('class-',id) as clase"))
                    ->whereNull("parent_id")
                    ->orderBy("nombre");

        $tmp = $query->get();

        foreach($tmp as $k => $row) {

            $data[] = $row;

            $query = DB::table("system_modulos")
                        ->select("id","nombre","route","icon","orden","parent_id",DB::raw("2 as nivel"),DB::raw("concat('class-',id,' ','class-$row->id') as clase"))
                        ->where("parent_id", $row->id)
                        ->orderBy("nombre");

            $tmp2 = $query->get();

            foreach($tmp2 as $k2 => $row2) {
                
                $data[] = $row2;

                $query = DB::table("system_modulos")
                            ->select("id","nombre","route","icon","orden","parent_id",DB::raw("3 as nivel"),DB::raw("concat('class-',id,' ','class-$row2->id',' ','class-$row->id') as clase"))
                            ->where("parent_id", $row2->id)
                            ->orderBy("nombre");
    
                $tmp3 = $query->get();
    
                foreach($tmp3 as $k3 => $row3) {
    
                    $data[] = $row3;
                }
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function sideBarModulos(Request $request) {

        $data = [];

        $query = DB::table("system_modulos")
                    ->select("id","nombre","route","icon","orden","parent_id")
                    ->whereNull("parent_id")
                    ->orderBy("orden");

        $tmp = $query->get();

        foreach($tmp as $k => $row) {

            $query = DB::table("system_modulos")
                        ->select("id","nombre","route","icon","orden","parent_id")
                        ->where("parent_id", $row->id)
                        ->orderBy("orden");

            $tmp2 = $query->get();

            foreach($tmp2 as $k2 => $row2) {

                if(!isset($row->items)) $row->items = [];

                $query = DB::table("system_modulos")
                            ->select("id","nombre","route","icon","orden","parent_id")
                            ->where("parent_id", $row2->id)
                            ->orderBy("orden");
    
                $tmp3 = $query->get();
    
                foreach($tmp3 as $k3 => $row3) {
    
                    if(!isset($row2->items)) $row2->items = [];
    
                    $row2->items[] = $row3;
                }

                $row->items[] = $row2;
            }

            $data[] = $row;
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
