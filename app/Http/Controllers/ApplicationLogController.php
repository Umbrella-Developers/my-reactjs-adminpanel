<?php

namespace App\Http\Controllers;

use App\Services\ApplicationLogService;
use Illuminate\Http\Request;

class ApplicationLogController extends Controller
{

    protected $applicationLog;
    public function __construct(ApplicationLogService $applicationLogService)
    {
        $this->applicationLog = $applicationLogService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null, $model = "\App\Models\ApplicationLog")
    {
        return $this->applicationLog->applicationLogIndex($request, $id, $model);
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
    public function store(Request $request, $id = null, $model = "\App\Models\ApplicationLog")
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id = null, $model = "\App\Models\ApplicationLog")
    {
        return $this->applicationLog->applicationLogShow($request, $id, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
