<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RolePermissionSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        //----------------------------------------------------------------
        
        // Comment::factory()
        // ->times(3)
        // ->create();
        
        //----------------------------------------------------------------
        
        /*
            if in case there is a need to add permkissiosn to permission table and assigned to any role in my case i assigned to Admin role ...
            One can add more permissions here as well.
         */
        // $permissionstypes = ['users', 'roles', 'permissions', 'configurations', 'comments', 'pages', 'auth'];
        // $permissionsList = ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy', 'search'];        
        // $pArray = [];
        // Foreach($permissionstypes as $type){
        //     foreach($permissionsList as $list){
        //         $pArray[] = $type . '-' . $list;
        //         $permission = new Permission;
        //         $permission->name = $type . '-' . $list;
        //         $permission->guard_name = 'sanctum';
        //         if($type == 'users'){
        //             $permission->action = 'Controllers/' . 'UserController@' . $list;
        //         }
        //         if($type == 'roles'){
        //             $permission->action = 'Controllers/' . 'RoleController@' . $list;
        //         }
        //         if($type == 'permissions'){
        //             $permission->action = 'Controllers/' . 'PermissionController@' . $list;
        //         }
        //         if($type == 'configurations'){
        //             $permission->action = 'Controllers/' . 'ConfigurationController@' . $list;
        //         }
        //         if($type == 'comments'){
        //             $permission->action = 'Controllers/' . 'CommentController@' . $list;
        //         }
        //         if($type == 'pages'){
        //             $permission->action = 'Controllers/' . 'PageController@' . $list;
        //         }
        //         if($type == 'auth'){
        //             $permission->action = 'Controllers/' . 'AuthController@' . $list;
        //         }                
        //         $permission->save();               
        //     }            
        // }
        // $role = Role::where('name', 'Admin')->first();
        // $role->syncPermissions($pArray);       
        
        //-----------------------------------------------------------------------------------------------------------
    }
}
