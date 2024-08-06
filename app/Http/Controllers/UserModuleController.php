<?php

namespace App\Http\Controllers;

use App\Models\UserModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = DB::table("user_modules")
                    ->leftJoin("system_modulos","system_modulos.id","user_modules.rol_id")
                    ->select("user_modules.id","system_modulos.nombre as modulo","user_modules.modulo_id")
                    // ->whereNull("user_modules.deleted_at")
                    ;

        $response = $query->get();

        return response()->json($response, 200);
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
    public function show(UserModule $userModule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserModule $userModule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserModule $userModule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserModule $userModule)
    {
        //
    }

    public function storeUserModules(Request $request) {
        
        UserModule::where('user_id', $request['user_id'])->delete();

        // print_r($request['user_modules']);
        // echo "user: ".auth()->user()->id;

        foreach($request['user_modules'] as $modulo_id) {
            
            $params = [
                'user_id' => $request['user_id'],
                'modulo_id' => $modulo_id
            ];

            UserModule::create($params);
        }

        return response()->json(["message" => 123]);
    }

    public function accessUserModules(Request $request) {

        $query = DB::table("user_modules")
                    ->leftJoin("system_modulos","system_modulos.id","user_modules.modulo_id")
                    ->select("user_modules.id","system_modulos.nombre as modulo","user_modules.modulo_id")
                    ->where("user_modules.user_id", $request['user_id'])
                    ;

        $response = $query->get();

        return response()->json($response, 200);
    }
}
