<?php

namespace App\Http\Controllers;

use App\Services\DonorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{

    protected $user;
    public function __construct(DonorService $donorService)
    {
        $this->user = $donorService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null, $model = "\App\Models\User")
    {
        //
        return $this->user->donorIndex($request, $id, $model);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id = null, $model = "\App\Models\User")
    {
        $roles = Role::all();
        return view('donors.create', compact('roles'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null, $model = "\App\Models\User")
    {
        return $this->user->donorStore($request, $id, $model);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id = null, $model = 'App\Models\User')
    {
        return $this->user->donorShow($request, $id, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        return $this->user->donorEdit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id = null, $model = 'App\Models\User')
    {
        return $this->user->donorUpdate($request, $id, $model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        return $this->user->donorDestroy($id);
    }

    public function search(){
        
    }
    /*
        Update password from logged in user end.
        Not in case of forgot password.
    */
    public function updatePassword(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->donorUpdatePassword($request, $id, $model);
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
        $user = User::findOrFail($userId);
        return view('donors.editProfile', compact('user', 'role'));
    }    
    
    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'password' => ['same:confirm_password'], 
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::findOrFail($userId);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->update();
        return back()->with('success', 'Profile Updated Successfully.');
    }

    /*
        Sending password reset email with passwordresettoken to users email address.
    */
    public function passwordResetEmail(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->donorPasswordResetEmail($request, $id, $model);
    }
    /*
        Will enter new password to generated URL woth password and confirm password included.
    */    
    public function newPassword(Request $request, $id = null, $model = 'App\Models\User'){
        return $this->user->donorNewPassword($request, $id, $model);
    }
}
