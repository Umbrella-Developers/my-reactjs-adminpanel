<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    protected $role = null;

    public function __construct(RoleService $roleService){
        $this->role = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null, $model = 'Spatie\Permission\Models\Role') {

        return $this->role->roleIndex($request, $id, $model);
    }

    public function create(Request $request)
    {
       // Fetch all permissions with the 'type' column
        $permissions = Permission::where('type', 'user-define')->get();

        // Group permissions by entity (first part of the name before the hyphen)
        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            // Check if the permission contains a hyphen
            if (strpos($permission->name, '-') !== false) {
                // Split permission name into entity and action (e.g., users-index becomes users and index)
                [$entity, $action] = explode('-', $permission->name, 2);

                // Group by entity (e.g., 'users', 'roles', etc.)
                $groupedPermissions[$entity][] = $permission;
            } else {
                // Handle permissions without hyphen
                $groupedPermissions['other'][] = $permission; // Group them separately
            }
        }

        return view('roles.create', compact('groupedPermissions'));

    }

    public function store(Request $request, $id = null, $model = 'Spatie\Permission\Models\Role')
    {        
        return $this->role->roleStore($request, $id, $model);
    }

    public function show(Request $request, $id = null, $model = 'Spatie\Permission\Models\Role')
    {

        return $this->role->roleShow($request, $id, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        return $this->role->roleEdit($id);
    }

    public function update(Request $request, $id = null, $model = 'Spatie\Permission\Models\Role')
    {
       return $this->role->roleUpdate($request, $id, $model);
    }

    public function destroy(Request $request, $id = null, $model = 'Spatie\Permission\Models\Role')
    {        
        return $this->role->roleDestroy($request, $id, $model);
    }

    public function association(Request $request, $id)
    {
        return $this->role->rolePermissionsAssociation($request, $id);
    }

    public function rolePermissions(Request $request, $id)
    {
        return $this->role->getRolePermissions($request, $id);
    }
}