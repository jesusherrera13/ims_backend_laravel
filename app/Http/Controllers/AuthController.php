<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request) {

        // return response()->json(['password' => Str::random(8)], 200);
        $user = User::where('email', $request['email'])->first();
        if($user) return response()->json(['success' => false, 'message' => 'El email `'.$request['email'].'` ya está en uso'], 200);

        $request['name'] = ucwords(mb_strtolower($request['email']));
        
        // GENERACIÓN DE PASSWORD RANDOM
        // $request['password'] = $request['password_confirmation'] = Str::random(8);

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $response = [
            'user' => $user,
            // 'password' =>$request['password']
        ];

        return response($response, 201);
    }

    public function login(Request $request) {

        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();
        // $user->tokens()->delete();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            
            return response([
                'success' => false,
                'message' => 'Error en usuario y/contraseña'
            ], 200);
        }

        if($user && !$user->email_verified_at) {
            return response([
                'success' => false,
                'message' => 'El usuario no ha sido verificado por el Administrador'
            ], 200);
        }
        
        $user = User::where('email', $fields['email'])->whereNotNull('email_verified_at')->first();
        // $user->tokens()->delete();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        unset($user->created_at);
        unset($user->email_verified_at);
        unset($user->updated_at);

        $response = [
            'success' => true,
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function logout(Request $request) {

        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out'
        ], 201);
    }

    public function userModules(Request $request) {
        /*
         select id,nombre,route,icon,orden,parent_id
        from ims.system_modulos
        where parent_id is null or parent_id=0
        order by orden;
         */

        $data = [];

        if($request['user_id'] == 1) {

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
        }
        else {
            $query = DB::table("system_modulos")
                        ->select("system_modulos.id","system_modulos.nombre","system_modulos.route","system_modulos.icon","system_modulos.orden","system_modulos.parent_id")
                        ->join("user_modules","user_modules.modulo_id","system_modulos.id")
                        ->whereNull("system_modulos.parent_id")
                        ->where("user_modules.user_id", $request['user_id'])
                        ->orderBy("system_modulos.orden");
    
            $tmp = $query->get();
    
            foreach($tmp as $k => $row) {
    
                $query = DB::table("system_modulos")
                            ->select("system_modulos.id","system_modulos.nombre","system_modulos.route","system_modulos.icon","system_modulos.orden","system_modulos.parent_id")
                            ->join("user_modules","user_modules.modulo_id","system_modulos.id")
                            ->where("system_modulos.parent_id", $row->id)
                            ->where("user_modules.user_id", $request['user_id'])
                            ->orderBy("system_modulos.orden");
    
                $tmp2 = $query->get();
    
                foreach($tmp2 as $k2 => $row2) {
    
                    if(!isset($row->items)) $row->items = [];
    
                    $query = DB::table("system_modulos")
                                ->select("system_modulos.id","system_modulos.nombre","system_modulos.route","system_modulos.icon","system_modulos.orden","system_modulos.parent_id")
                                ->join("user_modules","user_modules.modulo_id","system_modulos.id")
                                ->where("system_modulos.parent_id", $row2->id)
                                ->where("user_modules.user_id", $request['user_id'])
                                ->orderBy("system_modulos.orden");
        
                    $tmp3 = $query->get();
        
                    foreach($tmp3 as $k3 => $row3) {
        
                        if(!isset($row2->items)) $row2->items = [];
        
                        $row2->items[] = $row3;
                    }
    
                    $row->items[] = $row2;
                }
    
                $data[] = $row;
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function authSideBar(Request $request) {
        /*
         select id,nombre,route,icon,orden,parent_id
        from ims.system_modulos
        where parent_id is null or parent_id=0
        order by orden;
         */

        $data = [];
        // $tmp = [];

        if($request['user_id'] == 1) {

            // { header: authStore.system_modules[i].nombre }

            $query = DB::table("system_modulos")
                        ->select("id","nombre as header","route","icon","orden","parent_id")
                        ->whereNull("parent_id")
                        ->orderBy("orden");
    
            $tmp = $query->get();
            
    
            foreach($tmp as $k => $row) {

                $data[] = $row;
                // var menu = { title: authStore.system_modules[i].items[j].nombre, icon: MenuIcon, to: '#', children: [] };
    
                $query = DB::table("system_modulos")
                            ->select("id","nombre as title","route as to","icon","orden","parent_id")
                            ->where("parent_id", $row->id)
                            ->orderBy("orden");
    
                $tmp2 = $query->get();

                // print_r($tmp2);
    
                foreach($tmp2 as $k2 => $row2) {
                    

                   /*  menu.children.push({
                        title: authStore.system_modules[i].items[j].items[k].nombre,
                        icon: BrandChromeIcon,
                        to: authStore.system_modules[i].items[j].items[k].route
                    }); */
    
    
                    $query = DB::table("system_modulos")
                                ->select("id","nombre as title","route as to","icon","orden","parent_id")
                                ->where("parent_id", $row2->id)
                                ->orderBy("orden");
        
                    $tmp3 = $query->get();

    
                    foreach($tmp3 as $k3 => $row3) {
        
                        if(!isset($row2->children)) $row2->children = [];
        
                        $row2->children[] = $row3;
                    }

                    $data[] = $row2;
                    // $row->children[] = $row2;
                }
    
                
            }

            // $data = $tmp;
        }
        else {
            // { header: authStore.system_modules[i].nombre }

            $query = DB::table("system_modulos")
                        ->select("system_modulos.id","system_modulos.nombre as header","system_modulos.route as to","system_modulos.icon","system_modulos.orden","system_modulos.parent_id")
                        ->join("user_modules","user_modules.modulo_id","system_modulos.id")
                        ->whereNull("system_modulos.parent_id")
                        ->where("user_modules.user_id", $request['user_id'])
                        ->orderBy("system_modulos.orden");
    
            $tmp = $query->get();
    
            foreach($tmp as $k => $row) {
                // var menu = { title: authStore.system_modules[i].items[j].nombre, icon: MenuIcon, to: '#', children: [] };
                $data[] = $row;

                $query = DB::table("system_modulos")
                            ->select("system_modulos.id","system_modulos.nombre as title","system_modulos.route as to","system_modulos.icon","system_modulos.orden","system_modulos.parent_id")
                            ->join("user_modules","user_modules.modulo_id","system_modulos.id")
                            ->where("system_modulos.parent_id", $row->id)
                            ->where("user_modules.user_id", $request['user_id'])
                            ->orderBy("system_modulos.orden");
    
                $tmp2 = $query->get();
    
                foreach($tmp2 as $k2 => $row2) {
                    $data[] = $row2;
                    // if(!isset($row->items)) $row->items = [];
    
                    $query = DB::table("system_modulos")
                                ->select("system_modulos.id","system_modulos.nombre as title","system_modulos.route as to","system_modulos.icon","system_modulos.orden","system_modulos.parent_id")
                                ->join("user_modules","user_modules.modulo_id","system_modulos.id")
                                ->where("system_modulos.parent_id", $row2->id)
                                ->where("user_modules.user_id", $request['user_id'])
                                ->orderBy("system_modulos.orden");
        
                    $tmp3 = $query->get();
        
                    foreach($tmp3 as $k3 => $row3) {
        
                        if(!isset($row2->children)) $row2->children = [];
        
                        $row2->children[] = $row3;
                    }
    
                    $row->children[] = $row2;
                }
            }
        }

        return response()->json($data, 200);
    }

    public function registrarUsuario(Request $request) {
        return response()->json($request, 200);
    }
}
