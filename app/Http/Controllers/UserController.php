<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    protected $user;
    public function __construct(UserService $userService)
    {
        $this->user = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null, $model = "\App\Models\User")
    {
        //
        return $this->user->userIndex($request, $id, $model);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id = null, $model = "\App\Models\User")
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null, $model = "\App\Models\User")
    {
        return $this->user->userStore($request, $id, $model);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id = null, $model = 'App\Models\User')
    {
        return $this->user->userShow($request, $id, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        return $this->user->userEdit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id = null, $model = 'App\Models\User')
    {
        return $this->user->userUpdate($request, $id, $model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        return $this->user->userDestroy($id);
    }

    public function search(){
        
    }
    /*
        Update password from logged in user end.
        Not in case of forgot password.
    */
    public function updatePassword(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->userUpdatePassword($request, $id, $model);
    }

    /*
        Verify email if password is forgot. Before logging in to the system.
    */
    public function verifyEmail(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->userVerifyEmail($request, $id, $model);
    }    
    public function editProfile(Request $request){
        $user = Auth::user();
        $userId = $user->id;
        $role = $user->roles->first()->name;
        $role;
        $user = User::findOrFail($userId);
        return view('users.editProfile', compact('user', 'role'));
    }    
    
    public function updateProfile(Request $request)
    {
        return $this->user->updateProfile($request);
    }

    /*
        Sending password reset email with passwordresettoken to users email address.
    */
    public function passwordResetEmail(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->userPasswordResetEmail($request, $id, $model);
    }
    /*
        Sending password reset email with passwordresettoken to users email address.
    */
    public function updateResetPassword(Request $request){
        return $this->user->userUpdateResetPassword($request);
    }
    /*
        Will enter new password to generated URL woth password and confirm password included.
    */    
    public function newPassword(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->userNewPassword($request, $id, $model);
    }

    public function bakcupAssociation(){
        return view('users.bakcupAssociation');
        // return $this->user->userBakcupAssociation();
    }

    public function verifyUserEmail(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->userVerifyUserEmail($request, $id, $model);
    }
}
