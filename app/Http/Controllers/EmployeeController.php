<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return response()->json(Employee::all());
        $query = DB::table("employees")
                    // ->leftJoin("system_users_roles","system_users_roles.id","users.rol_id")
                    ->select("employees.id","employees.employee_id","employees.employee_name","employees.employee_last_name","employees.employee_second_last_name","employees.rfc")
                    // ->whereNull("users.deleted_at")
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
    public function show($id)
    {
        $data = Employee::select("employees.id","employees.employee_id","employees.employee_name","employees.employee_last_name","employees.employee_second_last_name","employees.rfc")
                // ->leftJoin("system_users_roles","system_users_roles.id","users.rol_id")
                ->where("employees.id", $id)
                ->first();

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
