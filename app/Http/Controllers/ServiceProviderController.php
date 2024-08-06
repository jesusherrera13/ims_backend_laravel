<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceProviderResource;
use Illuminate\Support\Facades\DB;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceProviderResource::collection(ServiceProvider::with('modules')->get());
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
    public function show(ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceProvider $serviceProvider)
    {
        //
    }

    public function sync(Request $request)
    {
        // SERVICE PROVIDER 1: FORTIA
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        
        $response = [
            'message' => 'Sin actualizaciones'
        ];

        if($request['service_provider_id'] == 1) {

            // $tmp = app(FortiaController::class)->getToken($request);
            // $response = json_decode($tmp->content(), true);

            // $request['token'] = $response['token'];

            if($request['module'] == "company") {
                $sync = app(FortiaController::class)->syncCompany($request);
                $response = json_decode($sync->content(), true);
            }
            else if($request['module'] == "business_name") {
                $sync = app(FortiaController::class)->syncBusinessName($request);
                $response = json_decode($sync->content(), true);
            }
            else if($request['module'] == "location") {
                $sync = app(FortiaController::class)->syncLocation($request);
                $response = json_decode($sync->content(), true);
            }
            else if($request['module'] == "department") {
                $sync = app(FortiaController::class)->syncDepartment($request);
                $response = json_decode($sync->content(), true);
            }
            else if($request['module'] == "employee") {
                $sync = app(FortiaController::class)->syncEmployee($request);
                $response = json_decode($sync->content(), true);
            }
            else if($request['module'] == "sync_all") {
                $sync = app(FortiaController::class)->syncAll($request);
                $response = json_decode($sync->content(), true);
            }

            /* if($response['status'] == 200) {

            }
            else {
                unset($response['token']);
            } */
        }

        return response()->json($response);
    }

    public function gtts(Request $request) {
        
    }
}
