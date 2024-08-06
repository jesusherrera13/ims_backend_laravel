<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index() {
        return UserResource::collection(User::all());
        // UserResource::collection(ServiceProvider::with('modules')->get())
        /* $query = DB::table("users")
                    ->leftJoin("system_users_roles","system_users_roles.id","users.rol_id")
                    ->select("users.id","users.name","users.email","users.rol_id","system_users_roles.nombre as rol")
                    ->whereNull("users.deleted_at");

        $response = $query->get(); */

        // return response()->json($response, 200);
    }

    public function show($id) {
        // return response()->json($user, 200);
        $data = User::select("users.id","users.name","users.email","users.rol_id","system_users_roles.nombre as rol")
                ->leftJoin("system_users_roles","system_users_roles.id","users.rol_id")
                ->where("users.id", $id)
                ->first();

        return response()->json($data, 200);
    }

    public function create(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'rol_id' => 'nullable',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'rol_id' => $fields['rol_id']
        ]);

        $response = [
            'user' => $user,
            // 'token' => $token
        ];

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user) {
        $fields = $request->validate([
            'name' => 'required|string',
            'rol_id' => 'nullable',
            //'password' => 'required|string|confirmed',
        ]);

        $user->update($fields);

        $response = [
            'user' => $user,
            // 'token' => $token
        ];

        return response()->json($response, 201);
    }


    public function destroy(User $user) {
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado'], 200);
    }
}
