<?php

namespace App\Services;

use App\Mail\PasswordResetEmail;
use App\Mail\SendPasswordResetSuccessEmail;
use App\Models\Configuration;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Traits\ServiceTrait;
use Carbon\Carbon;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Mail;
use Symfony\Component\HttpFoundation\Response;

/*
    Store, Destroy, Update, Index, Show.
    Note. Service is being created for UserController. 
    And logic is being handled in UserService. And base structure is given in UserController.
*/
class UserService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;
    // public function getUserAuthorization()
    // {
    //     return Session::get('user_id');
    // }

    /*
        userIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function userIndex(Request $request, $id = null, $model){        
        $obj = $model::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Donor');
        })->get();
        return $this->index($request, $id, $model, $obj);
    }

    /*
        userShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function userShow(Request $request, $id, $model){   
        return $this->show($request, $id, $model, $search = false);    
    }

    public function userStore(Request $request, $id, $model){
        $requestArray = null;    
        //Validated
        $rules = [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone_number' => 'required|unique:users',
            'role_id' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            
        ];
        
        // Validate the request using the helper function
        validateRequest($request, $rules);

        $data = $this->store($request, $id, $model, $requestArray);
        $user = $data['data']['data']['user'] ?? null;
        if($user) {
            
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $request->file('avatar')->storeAs('avatars', $filename, 'spaces');
                $avatarPath = Storage::disk('spaces')->url('avatars/'.$filename);
                $user->picture = $avatarPath;
            }
            
            
            if($request->role_id){
                $user->roles()->sync([$request->role_id]);
            }
            $user->save();
            $staff_welcome_email = getSetting('staff_welcome_email');
            
            if($staff_welcome_email) {
                PasswordResetToken::where('email', $user->email)->delete();
                $dataArray = [
                    'email' => $user->email,
                    'token' => Str::random(60),
                    'created_at' => Carbon::now()
                ];
                $passwordResetToken = PasswordResetToken::create(
                    $dataArray
                );
                // Sending email can be handled asynchronously or deferred using queues to avoid memory issues
                
                // prepare email
                $reset_link = url('/') . '/user/reset-password/?secret_token=' . $passwordResetToken->token;

                $full_name = $user->first_name . ' ' . $user->last_name;
                $patterns = [];
                $patterns = ['full_name' => $full_name, 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'reset_link' => $reset_link, 'login_link' => url('/')];
                
                prepareEmailTemplate($user->email, 'user_register_email', $patterns, "Welcome to ITAD portal");        
            }
            
            // send email
            //$this->sendEmail($request->email, $model);        
            return redirect()->route('users.index')->with('success', 'User Created Successfully');
        }
        
        return redirect()->back()->with('error', 'Please try again.');
    }
    
    /*
        userEdit function is fetching user for edit with ID.
    */ 
    public function userEdit($id){
        $user = User::find($id);
        $role_id = $user->roles->first()->id;
        $roles = Role::all();
        return success('users.update', ['data' => [ 
            'user_id' => $id,                   
            'user' => $user,
            'roles' => $roles,
            'role_id' => $role_id,
            'status' => true, 
            'message' =>'user Edit.',
            'statusCode' => 200,
        ]]);
    }


    /*
        userUpdate function is update data. ServiceTrait where all Services are handled. In update function
        For role_id either you pass ID or if not you can keep it null or empty.
    */ 
    public function userUpdate(Request $request, $id, $model){        
        $user = $model::findOrFail($id);
        //Validated
        $rules = [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'c_password' => $request->password ? 'required|same:password' : 'nullable',
            'phone_number' => 'required|unique:users,phone_number,' . $id,
            'role_id' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];

        validateRequest($request, $rules);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        if($request->role_id){
            $user->roles()->sync([$request->role_id]);
        }
        // Update account status (is_active)
        $user->is_active = $request->has('is_active') ? 1 : 0;
        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('avatars', $filename, 'spaces');
            $avatarPath = Storage::disk('spaces')->url('avatars/'.$filename);
            $user->picture = $avatarPath;
        }
        $user->save();
        if ($request->has('role_id')) {
            $role = Role::findOrFail($request->input('role_id'));
            $user->assignRole($role);
        }
        return redirect()->route('users.index')->with('success', 'User Updated Successfully');
    }

    /*
        userDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
    */ 
    public function userDestroy($id){
        $user=User::findOrFail($id);
        if($user->delete()) {
            return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
        }
        return redirect()->route('users.index')->with('error', 'There are some problem in form submission. Please try again.');  
    }

    /*
        userUpdatePassword function update user password.
    */ 
    public function userUpdatePassword(Request $request, $id, $model){
        $validateUser = Validator::make($request->all(), 
        [
            'password' => ['required', 
                            Password::min(8), 
                            'same:confirm_password'], 
            'confirm_password' => 'required',
        ]);


        // validateAll($validateUser);


        $user = $model::findorFail($id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        if($user->save()) 
        {            
            return success('users.index', ['data' => [                    
                'status' => true, 
                'message' =>'Password updated successfully.',
                'statusCode' => 200,
            ]]);  
        } else {
            return success('users.index', ['data' => [                    
                'status' => true, 
                'message' =>'There are some problem in form submission.',
                'statusCode' => 200,
            ]]);  
        }
    }

    /*
        userVerifyEmail function verify user email for password reset.
    */ 
    public function userVerifyEmail(Request $request, $id, $model){
        return view('users.verifyEmail');
    }



    /*
        userPasswordResetEmail function Once clicked on URL from the email.Will send Redirect URL to let user to enter Password and Confirm Password.
    */     
    public function userPasswordResetEmail(Request $request, $id, $model){
        // Validate the token        
        $data = PasswordResetToken::where('token', $request->secret_token)->first();
        if($data){  
            return view('users.passwordReset', ['token' => $data->token]);
        }else{
            return redirect('login')->withErrors(['Link has expired on invalid link is provided.']);
        }
        
    }
    public function userUpdateResetPassword(Request $request){
        // Validate the token        
        $data = PasswordResetToken::where('token', $request->secret_token)->first();
        
        if($data){  
            $validator = Validator::make($request->all(), [
                'password' => ['required', 'same:c_password'],
                'c_password' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 201,
                    'message' => 'Password and Confirm Password do not match.',
                ]);
            }
            $user = User::where('email', $data->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            // reset 
            $full_name = $user->first_name . ' ' . $user->last_name;
            $patterns = [];
            $patterns = ['full_name' => $full_name, 'first_name' => $user->first_name, 'last_name' => $user->last_name];
            prepareEmailTemplate($user->email, 'password_reset_success', $patterns, "Password Reset Successfully");

            PasswordResetToken::where('email', $user->email)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Password has been changed.',
                'redirect_url' => route('login'),
            ]);

        } else {
            return response()->json([
                'error' => 201,
                'message' => 'Auth token does not match.',
            ]);
        }
        
    }

    /*
        userNewPassword function verify token and update user password. At the end Delete password reset token from db.
    */ 
    public function userNewPassword(Request $request, $id, $model){
        //Validate input
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => ['required', Password::min(8), 'same:confirm_password'], 
            'confirm_password' => 'required',
            'secret_token' => 'required' 
        ];
        // validateRequest($request, $rules);
        $password = $request->password;
        // Validate the token
        $tokenData = PasswordResetToken::where('token', $request->secret_token)->first();
        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData){            
            return error('users.index', ['data' => [                    
                'data' => null,
                'status' => false, 
                'message' =>'Reset Token Not Verified.',
                'statusCode' => 401,
            ]]);        
        }
        $user = User::where('email', $tokenData->email)->first();
        if (!$user){
            return error('users.index', ['data' => [                    
                'data' => null,
                'status' => false, 
                'message' =>'Email not found.',
                'statusCode' => 401,
            ]]);           
        }
        $user->password = \Hash::make($password);
        $user->update(); //or $user->save();        

        //Delete the token
        PasswordResetToken::where('email', $user->email)->delete();

        //Send Email Reset Success Email
        if ($this->sendSuccessEmail($tokenData->email, $model)) {
            return success('users.index', ['data' => [                    
                'data' => $tokenData->email,
                'status' => true, 
                'message' =>'Password was reset successfully.',
                'statusCode' => 200,
            ]]);
        } else {
            return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
        }
    }

    /*
        sendSuccessEmail This function will send email about successfully password updated.
    */ 
    public function sendSuccessEmail($email, $model){
        $user = $model::select('name', 'email')->where('email', $email)->first();        
        $name = $user->name;
        $configurations = Configuration::where('name', 'password_reset_success')->first();
        $replacedString = Str::replace('replace_name', $name, $configurations->value);    
        $replacedString = Str::replace('replace_message', 'Congratulations your password is updated successfully...', $replacedString);        
        emailNotifcationForUser($user->email, $mail = 'App\Mail\SendPasswordResetSuccessEmail', $replacedString);
        return true;
    }
  
    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $rules = [
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'password' => ['same:c_password'],
        ];
        validateRequest($request, $rules);
        
        
        $user = User::findOrFail($userId);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        
        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('avatars', $filename, 'spaces');
            $avatarPath = Storage::disk('spaces')->url('avatars/'.$filename);
            $user->picture = $avatarPath;
        }
        $user->update();
        return back()->with('success', 'Profile Updated Successfully.');
        
    }
    
    public function userBakcupAssociation(){
        return redirect('users/bakcupAssociation');   
    }

    public function userVerifyUserEmail(Request $request, $id, $model){
        $user = $model::where('email', $request->email)->first();
    
        if ($user) {
            PasswordResetToken::where('email', $user->email)->delete();
            $dataArray = [
                'email' => $user->email,
                'token' => Str::random(60),
                'created_at' => Carbon::now()
            ];
            $passwordResetToken = PasswordResetToken::create(
                $dataArray
            );
            // Sending email can be handled asynchronously or deferred using queues to avoid memory issues
            
            // prepare email
            $reset_link = url('/') . '/user/reset-password/?secret_token=' . $passwordResetToken->token;
            $full_name = $user->first_name . ' ' . $user->last_name;
            $patterns = [];
            $patterns = ['full_name' => $full_name, 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'reset_link' => $reset_link];
            prepareEmailTemplate($user->email, 'password_reset_email', $patterns, "Password Reset Request");
            return response()->json([
                'success' => 200,
                'message' => 'Password Reset Email Sent Successfully. Please check your email.'
            ]);
        }
    
        return response()->json([
            'error' => 201,
            'message' => 'Email Not Found'
        ]);
    }

}
