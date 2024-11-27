<?php

namespace App\Services;

use App\Traits\ServiceTrait;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

/*
    Store, Destroy, Update, Index, Show.
    Note. Service is being created for PermissionController. 
    And logic is being handled in PermissionService. And base structure is given in PermissionController.
*/
class PermissionService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;

    protected $permissionsList = ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy', 'search'];
    protected $restricted_permissions = ['users', 'roles', 'permissions', 'auth'];

    /*
        permissionIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function permissionIndex(Request $request, $id, $model){
        $data = $this->index($request, $id, $model);
        return $data;        
    }

    /*
        permissionShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function permissionShow(Request $request, $id, $model){
        if(empty($request)){
            return $this->show($request, $id, $model, $search = true);
        }
        return $this->show($request, $id, $model, $search = false);  
    }

    /*
        permissionStore function is storing  data. ServiceTrait where all Services are handled. In store function
    */ 
    public function permissionStore(Request $request, $id, $model){
        //Validated
        $validatePermission = Validator::make($request->all(), 
        [
            'name' => 'required',
            'action' => 'required',
            // 'guard_name' => 'required'
        ]);

        // validateAll($validatePermission);
        $requestArray = null;            
        foreach($this->permissionsList as $key => $list){
            $requestArray[$key]['guard_name'] = 'sanctum';
            $requestArray[$key]['action'] = 'Controllers/' . ucfirst(rtrim($request->name, 's')) . 'Controller@' . $list;
            $requestArray[$key]['name'] = $request->name . '-' . $list;
            
        }                 
        // Artisan::call('make:controller ' . ucfirst(rtrim($request->name, 's')) . 'Controller --resource');       
        return $this->store($request, $id, $model, $requestArray);       
    }

    /*
        permissionEdit function is update data. ServiceTrait where all Services are handled. In update function
    */ 
    public function permissionEdit($id){
        $permission = Permission::find($id);
        $explodeName = explode('-', $permission->name);
        $permission['name'] = $explodeName[0];        
        return success('permissions.index', ['data' => [                    
            'status' => true, 
            'message' => 'Permissions information fetched to edit.',
            'permission' => $permission,
            'statusCode' => 200,
        ]]);
    }

    /*
        permissionDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
        Also it detaches from Roles. Where deleted permisison is assigned.
    */ 
    public function permissionDestroy(Request $request, $id){
        $permission = Permission::where('id', $id)->first();

        $explodeName = explode('-', $permission->name);
        $permission['name'] = $explodeName[0];

        // Check if the permission is one of the special permissions which are not removable
        if (in_array($permission['name'], $this->restricted_permissions)) {
            return error('permissions.index', ['data' => [                    
                'status' => false,
                'message' => 'This permission cannot be removed.',
                'statusCode' => 403,
            ]]);
        }

        foreach($this->permissionsList as $per){
            $permissionName = $permission['name'] . '-' . $per; // creating name of permission
            $permissionExists = Permission::where('name', $permissionName)->exists();
            if ($permissionExists) {
                Permission::where('name', $permissionName)->delete();
            }
        }
        return success('permissions.index', ['data' => [                    
            'status' => true, 
            'message' => 'Selected Permission Removed.',
            'statusCode' => 200,
        ]]);
    }

    /*
        permissionUpdate function is update data. ServiceTrait where all Services are handled. In update function
    */ 
    public function permissionUpdate(Request $request, $id){        
        $permission = Permission::where('id', $id)->first();
        $request->validate([
            'name' => 'required',
            'action' => 'required',
        ]);

        $explodeName = explode('-', $permission->name);
        $permission['name'] = $explodeName[0];
        $newName = $request->name;

        // Check if the permission is one of the special permissions which are not editable
        if (in_array($permission['name'], $this->restricted_permissions)) {            
            return error('permissions.index', ['data' => [                    
                'status' => false,
                'message' => 'This permission cannot be updated.',
                'statusCode' => 403,
            ]]);
        }

        foreach($this->permissionsList as $per){
            $permissionName = $permission['name'] . '-' . $per; // creating name of permission
            $permissionUpdate = Permission::where('name', $permissionName)->first();
            $permissionUpdate->update([
                "name" => $newName . '-' . $per
            ]);
        }
        return success('permissions.index', ['data' => [                    
            'status' => true, 
            'message' => 'Permissions Updated Successfully.',
            'statusCode' => 200,
        ]]);
    }

}
