<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // basic permissions creation ...
        $permissionstypes = ['users', 'roles', 'permissions', 'configurations', 'dashboards', 'pages', 'auth'];
        $permissionsList = ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy', 'search'];        
        $role = Role::where('name', 'Admin')->first();
        foreach($permissionstypes as $type){
            $updatedArray = [];            
            foreach($permissionsList as $key => $list){
                $permissionCreate = Permission::create([
                    'name' => $type . '-' . $list,
                    'guard_name' => 'sanctum',
                    'created_at' => Carbon::now(),
                    'action' => 'Controllers/' . ucfirst(rtrim($type, 's')) . 'Controller@' . $list,
                    'type' => 'user-define',
                ]);  
            }           
        }    
        
        $extraPermissionsTypes = ['roles-rolePermissionAssociation', 'users-forgotPassword', 'users-passwordResetEmail', 'users-newPassword', 'users-verifyEmail', 'users-updatePassword', 'auth-logout', 'auth-association'];
        $extraPermissionControllers = ['Controllers/RoleController@association', 'Controllers/UserController@forgotPassword', 'Controllers/UserController@passwordResetEmail', 'Controllers/UserController@newPassword', 'Controllers/UserController@verifyEmail', 'Controllers/UserController@updatePassword', 'Controllers/AuthController@logout', 'Controllers/AuthController@association'];
        
        foreach($extraPermissionsTypes as $key => $extraPermission){            
            $extraPermissionCreate = Permission::create([
                'name' => $extraPermission,
                'guard_name' => 'sanctum',
                'created_at' => Carbon::now(),
                'action' => $extraPermissionControllers[$key],
                'type' => 'user-define',
            ]); 
        }
        // Assigning all permissions to Role name Admin...
        $role->givePermissionTo(Permission::whereGuardName('sanctum')->get());
    }
}
