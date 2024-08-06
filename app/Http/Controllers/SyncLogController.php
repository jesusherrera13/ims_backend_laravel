<?php

namespace App\Http\Controllers;

use App\Models\SyncLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncLogController extends Controller
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
    public function show(SyncLog $syncLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SyncLog $syncLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SyncLog $syncLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SyncLog $syncLog)
    {
        //
    }

    public function syncLogUnlock(Request $request)
    {
        SyncLog::where('service_provider_id', $request['service_provider_id'])->where('status', 1)->update(['status' => 2]);

        return response()->json([ 'message' => 'Log de sincronización actualizado']);
    }
}
