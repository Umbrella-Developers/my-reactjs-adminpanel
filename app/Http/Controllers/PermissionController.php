<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissions = null;

    public function __construct(PermissionService $permissionsService){
        $this->permissions = $permissionsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null, $model = 'Spatie\Permission\Models\Permission')
    {
        return $this->permissions->permissionIndex($request, $id, $model);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null, $model = 'Spatie\Permission\Models\Permission')
    {
        return $this->permissions->permissionStore($request, $id, $model);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id = null, $model = 'Spatie\Permission\Models\Permission')
    {
        return $this->permissions->permissionShow($request, $id, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return $this->permissions->permissionEdit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id = null, $model = 'Spatie\Permission\Models\Permission')
    {
        return $this->permissions->permissionUpdate($request, $id, $model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id = null, $model = 'Spatie\Permission\Models\Permission')
    {
        return $this->permissions->permissionDestroy($request, $id, $model);
    }
}
