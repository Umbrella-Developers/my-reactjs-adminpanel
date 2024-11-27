<?php

namespace App\Services;

use App\Models\RoleHasPermission;
use App\Traits\ServiceTrait;
// use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/*
    Store, Destroy, Update, Index, Show.
    Note. Service is being created for RoleController. 
    And logic is being handled in RoleService. And base structure is given in RoleController.
*/
class RoleService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;

    /*
        roleIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function roleIndex(Request $request, $id, $model){
        return $this->index($request, $id, $model);
    }

    /*
        roleStore function is storing  data. ServiceTrait where all Services are handled. In store function
    */ 
    public function roleStore(Request $request, $id, $model)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Create the new role
        $role = Role::create(['name' => $request->name]);

        // Prepare the selected permissions
        $selectedPermissions = $request->permissions ?? [];

        // Define entities (the same entities as in your view)
        $entities = ['entity1', 'entity2', 'entity3']; // Replace with your actual entity names

        // Check if group checkboxes were selected and add corresponding permissions
        foreach ($entities as $entity) {
            if ($request->has('view_' . $entity)) {
                $selectedPermissions = array_merge($selectedPermissions, ['index', 'show']);
            }
            if ($request->has('store_' . $entity)) {
                $selectedPermissions = array_merge($selectedPermissions, ['create', 'store']);
            }
            if ($request->has('update_' . $entity)) {
                $selectedPermissions = array_merge($selectedPermissions, ['edit', 'update']);
            }
        }

        // Fetch all pre-defined permissions from the database
        $preDefinedPermissions = Permission::where('type', 'pre-define')->pluck('name')->toArray();

        // Merge pre-defined permissions with the selected permissions
        $selectedPermissions = array_merge($selectedPermissions, $preDefinedPermissions);

        // Remove duplicates from selected permissions
        $selectedPermissions = array_unique($selectedPermissions);

        // Assign permissions to the role
        $role->syncPermissions($selectedPermissions); // Sync permissions (attach/detach)

        // Redirect or return response
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }



    /*
        roleShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function roleShow(Request $request, $id, $model){        
        if(empty($request)){
            return $this->show($request, $id, $model, $search = true);
        }
        return $this->show($request, $id, $model, $search = false);
    }

    /*
        roleDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
    */ 
    public function roleDestroy(Request $request, $id, $model)
    {
        // Find the role to delete
        $role = Role::findOrFail($id);
        
        // Ensure we're not deleting protected roles
        if ($role->name != 'Admin' && $role->name != 'Donor' && $role->name != 'Super Admin' || $role->name == 'Backup Role Association') {
            if (isset($role)) {
                // Get all users associated with the role
                $usersWithRole = \App\Models\User::role($role->name)->get(); // Spatie method

                // Find the backup role (ID = 35)
                $backupRole = Role::findOrFail(35);

                // Loop through users and reassign them to the backup role
                foreach ($usersWithRole as $user) {
                    // Remove the current role and assign the backup role
                    $user->removeRole($role);    // Spatie method
                    $user->assignRole($backupRole);  // Spatie method
                }

                // Detach permissions from the role and delete it
                $role->permissions()->detach();
                $data = $this->destroy($request, $id, $model); // Actually delete the role


                return redirect()->route('roles.index')->with('success', 'Role deleted and users reassigned to Backup Role Association.');
            }

            return redirect()->route('roles.index')->with('error', 'There was a problem deleting the role. Please try again.');
        }

        return redirect()->route('roles.index')->with('error', 'Protected roles cannot be deleted.');
    }

    public function roleEdit($id)
    {
        $groupCount = 0;
        $totalpermissionsCount = 0;
        $selectedAllButton = false;
        $role = Role::find($id);

        // Check if the role exists
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Role not found.');
        }

        // Allow editing if the role is not 'Admin', 'Donor', or 'Super Admin'
        if (!in_array($role->name, ['Admin', 'Donor', 'Super Admin'])) {
            $totalPermissions = Permission::where('type', 'user-define')->get();
            foreach($totalPermissions as $permissions){
                $rolaHasPermissions = RoleHasPermission::where('role_id', $id)->where('permission_id', $permissions->id)->first();
                if($rolaHasPermissions){
                    $totalpermissionsCount++;
                }
            }
            foreach(getGroupedPermissions() as $permissions){
                    $groupCount += count($permissions); 
            }
             return success('roles.update', [                    
                'role' => $role,
                'groupedPermissions' => getGroupedPermissions(), // Use the helper method
                'totalpermissionsCount' => $totalpermissionsCount == count($totalPermissions) ? true : false,
                'status' => true,
                'message' => 'Role Edit.',
                'statusCode' => 200,
            ]);
        }

        return redirect()->route('roles.index')->with('error', 'You are not allowed to edit this role.');
    }


    /*
        roleUpdate function is update data. ServiceTrait where all Services are handled. In update function
    */ 
    public function roleUpdate(Request $request, $id, $model){           
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Find the existing role
        $role = Role::findOrFail($id);

        // Update the role name
        $role->name = $request->name;
        $role->save();

        // Prepare the selected permissions
        $selectedPermissions = $request->permissions ?? [];
        

        // Define entities (the same entities as in your view)
        $entities = ['entity1', 'entity2', 'entity3']; // Replace with your actual entity names

        // Check if group checkboxes were selected and add corresponding permissions
        foreach ($entities as $entity) {
            if ($request->has('view_' . $entity)) {
                $selectedPermissions = array_merge($selectedPermissions, ['index', 'show']);
            }
            if ($request->has('store_' . $entity)) {
                $selectedPermissions = array_merge($selectedPermissions, ['create', 'store']);
            }
            if ($request->has('update_' . $entity)) {
                $selectedPermissions = array_merge($selectedPermissions, ['edit', 'update']);
            }
        }

        // Fetch all pre-defined permissions from the database
        $preDefinedPermissions = Permission::where('type', 'pre-define')->pluck('name')->toArray();

        // Merge pre-defined permissions with the selected permissions
        $selectedPermissions = array_merge($selectedPermissions, $preDefinedPermissions);

        // Remove duplicates from selected permissions
        $selectedPermissions = array_unique($selectedPermissions);

        // Sync permissions with the role (attach/detach as needed)
        $role->syncPermissions($selectedPermissions);

        // Redirect or return response
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /*
        rolePermissionsAssociation function is being used to Associate already stored permissions wtih role selected.
    */ 

    public function rolePermissionsAssociation(Request $request, $id)
    {
        $totalPermissions = count($request->permission_id);
        foreach($request->permission_id as $data){
            $split = explode("-",$data);
            if($split[1] == 'create'){
                // Get the existing permission_id values
                $permissionIds = $request->input('permission_id');

                // Add the new value to the array with a new key
                $newPermissionIdKey = max(array_keys($permissionIds)) + 1; // This will get the next integer key
                $newPermissionIdValue = $split[0] . '-' . 'store'; // Replace 'new-permission' with your desired value
                $permissionIds[$newPermissionIdKey] = $newPermissionIdValue;

                // Update the request with the new array
                $request->merge(['permission_id' => $permissionIds]);
            }
            if($split[1] == 'update'){
                // Get the existing permission_id values
                $permissionIds = $request->input('permission_id');

                // Add the new value to the array with a new key
                $newPermissionIdKey = max(array_keys($permissionIds)) + 1; // This will get the next integer key
                $newPermissionIdValue = $split[0] . '-' . 'edit'; // Replace 'new-permission' with your desired value
                $permissionIds[$newPermissionIdKey] = $newPermissionIdValue;

                // Update the request with the new array
                $request->merge(['permission_id' => $permissionIds]);
            }
        }

        $role = Role::find($id);
        if (!$role) {
            return error('roles.index', ['data' => [                    
                'status' => false,
                'message' => 'Role not found.',
                'statusCode' => 404,
            ]]);
        }
        dd('ads');
        // Revoke all existing permissions
        $role->revokePermissionTo($role->permissions);

        // Re-assign selected permissions   
        foreach($request->permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        return success('roles.index', ['data' => [                    
            'status' => true, 
            'message' => 'Associated Permission/s To Selected Role.',
            'statusCode' => 200,
        ]]);    
    }

    /*
        getRolePermissions function is being used to get assigned permissions to selected role.
    */ 
    public function getRolePermissions(Request $request, $id){
        $role = Role::findOrFail($id);
        return success('roles.index', ['data' => [                    
            'status' => true, 
            'data' => $role->permissions,
            'statusCode' => 200,
        ]]);    
    }

}
