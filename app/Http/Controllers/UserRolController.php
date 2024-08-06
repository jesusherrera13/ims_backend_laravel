<?php

namespace App\Http\Controllers;

use App\Models\UserRol;
use Illuminate\Http\Request;

class UserRolController extends Controller
{

    public function index()
    {
        return response()->json(UserRol::all(), 200);
    }

    public function create(Request $request) {
        $fields = $request->validate([
            'nombre' => 'required|string',
        ]);

        $userRol = UserRol::create([
            'nombre' => $fields['nombre'],
            
        ]);

        $response = [
            'userRol' => $userRol,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    public function show(UserRol $userRol)
    {
        return response()->json($userRol, 200);
    }

    public function update(Request $request, UserRol $rol)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            // 'password' => 'required|string|confirmed',
        ]);

        $rol->update($fields);

        $response = [
            'userRol' => $rol,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }

    public function destroy(UserRol $rol)
    {
        $rol->delete();
        return response()->json(['message' => 'Rol eliminado'], 200);
    }
    
}
