<?php

namespace App\Http\Controllers;

use App\Models\UserPlaza;
use Illuminate\Http\Request;
use App\Http\Requests\UserPlazaCreateRequest;

class UserPlazaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(UserPlaza $userPlaza)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserPlaza $userPlaza)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserPlaza $userPlaza)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPlaza $userPlaza)
    {
        //
    }

    public function storeUserPlazas(Request $request) {
        
        UserPlaza::where('user_id', $request['user_id'])->delete();

        foreach($request['user_plazas'] as $plaza_id) {
            
            $params = [
                'user_id' => $request['user_id'],
                'plaza_id' => $plaza_id
            ];

            UserPlaza::create($params);
        }

        return response()->json(["message" => 123]);
    }
}
