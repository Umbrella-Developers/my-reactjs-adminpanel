<?php

namespace App\Services;

use App\Mail\PasswordResetEmail;
use App\Mail\SendPasswordResetSuccessEmail;
use App\Models\Configuration;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Traits\ServiceTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\AsanaDonation;



/*
    Store, Destroy, Update, Index, Show.
    Note. Service is being created for UserController. 
    And logic is being handled in DonorService. And base structure is given in UserController.
*/
class DonorService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;
   
    /*
        donorIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function donorIndex(Request $request, $id = null, $model){        
        $data = $model::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'Donor');
        })->orderBy('id', 'desc')->get();
        //return $this->index($request, $id, $model, $data);
        return success('donors.index', ['data' => [                    
            'user' => $data,
            'status' => true,
            'message' => 'All Donor Data.',
            'statusCode' => 200,
        ]]);  
    }

    /*
        donorShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function donorShow(Request $request, $id, $model){   
        return $this->show($request, $id, $model, $search = false);    
    }

    public function donorStore(Request $request, $id, $model){    
        $requestArray = null;
        
        //Validated
        $rules = [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone_number' => 'required|unique:users',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'tech_donor_customer_id'=> 'required|unique:users,tech_donor_customer_id',
            
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
            $user->save();
            $model::where('email', $request->email)->first()->assignRole('Donor');
            $donor_welcome_email = getSetting('donor_welcome_email');
            
            if($donor_welcome_email) {
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
            return redirect()->route('donors.index')->with('success', 'User Created Successfully');
        }
        return redirect()->back()->with('error', 'Try again later.');
    }
    
    /*
        donorEdit function is fetching user for edit with ID.
    */ 
    public function donorEdit($id){
        $user = User::find($id);
        
        return success('donors.update', ['data' => [                    
            'user' => $user,
            'status' => true, 
            'message' =>'user Edit.',
            'statusCode' => 200,
        ]]);
    }

   

    /*
        donorUpdate function is update data. ServiceTrait where all Services are handled. In update function
        For role_id either you pass ID or if not you can keep it null or empty.
    */ 
    public function donorUpdate(Request $request, $id, $model){     
        //Validated
        $rules = [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'c_password' => $request->password ? 'required|same:password' : 'nullable',
            'phone_number' => 'required|unique:users,phone_number,' . $id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'company_address' => 'required',
            'company_name' => 'required',
            'tech_donor_customer_id'=> 'unique:users,tech_donor_customer_id,' . $id, 
        ];

        validateRequest($request, $rules);                               
        $user = $model::findOrFail($id);
        //$record = [];
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        //$user->tech_donor_customer_id = $request->tech_donor_customer_id;
        $user->company_name = $request->company_name;
        $user->company_website = $request->company_website;
        $user->company_address = $request->company_address;
              
        
        // Update account status (is_active)
        $user->is_active = $request->has('is_active') ? 1 : 0;
        
        // Check if password is given and matches c_password
        if ($request->filled('password') && $request->password === $request->c_password) {
            $user->password = bcrypt($request->password);  // Hash and set the password
        }
        // Handle avatar upload if provided
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
        if ($request->has('role_id')) {
            $role = Role::findOrFail($request->input('role_id'));
            $user->assignRole($role);
        }
        return redirect()->route('donors.index')->with('success', 'Donor Updated Successfully');
    }

    /*
        donorDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
    */ 
    public function donorDestroy($id){
        $user=User::findOrFail($id);
        
        if($user) {
            // Find all donations related to the given $id
            $donations = AsanaDonation::where('user_id', $id)->get();

            
            if($donations) {
                // Loop through each donation and delete related custom fields and the donation itself
                foreach ($donations as $donation) {
                    // Delete all related custom fields (AsanaDonationField)
                    $donation->fields()->delete();
                    
                    // Delete the donation itself
                    $donation->delete();
                }
            }
            if($user->delete()) {
                return redirect()->route('donors.index')->with('success', 'Donor Deleted Successfully');
            }
        }
        return redirect()->route('donors.index')->with('error', 'There are some problem in form submission. Please try again.');
        
    }

    /*
        donorUpdatePassword function update user password.
    */ 
    public function donorUpdatePassword(Request $request, $id, $model){
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
            return success('donors.index', ['data' => [                    
                'status' => true, 
                'message' =>'Password updated successfully.',
                'statusCode' => 200,
            ]]);  
        } else {
            return success('donors.index', ['data' => [                    
                'status' => true, 
                'message' =>'There are some problem in form submission.',
                'statusCode' => 200,
            ]]);  
        }
    }

    /*
        donorVerifyEmail function verify user email for password reset.
    */ 
    public function donorVerifyEmail(Request $request, $id, $model){
        $data = $model::where('email', $request->email)->first();
        if($data){
            $dataArray = [
                'email' => $data->email,
                'token' => Str::random(60),
                'created_at' => Carbon::now()
            ];
            $passwordResetToken = PasswordResetToken::create(
                $dataArray
            );
            $this->donorSendResetEmail($passwordResetToken->email, $passwordResetToken->token, $model);            
            return success('donors.index', ['data' => [                    
                'data' => $data,
                'status' => true, 
                'message' =>'User Email Verified.',
                'statusCode' => 200,
            ]]);
        }else{
            return error('donors.index', ['data' => [                    
                'data' => null,
                'status' => false, 
                'message' =>'User Email Not Verified.',
                'statusCode' => 401,
            ]]);
        }
    }

    /*
        donorSendResetEmail function sends email with password reset token after verifying email of the user.
    */ 
    private function donorSendResetEmail($email, $token, $model){
        $user = $model::select('name', 'email')->where('email', $email)->first();
        $url = url('/') . '/users/passwordResetEmail/?secret_token=' . $token . '&email=' . urlencode($user->email);
        $name = $user->name;
        $configurations = Configuration::where('name', 'password_reset_email')->first();
        $replacedString = Str::replace('replace_name', $name, $configurations->value);
        $replacedString = Str::replace('replace_url', $url, $replacedString);
        $replacedString = Str::replace('replace_message', 'Given is the URL you need to reach for your password reset. Will be used once only...', $replacedString);        
        emailNotifcationForUser($email, $mail = 'App\Mail\PasswordResetEmail', $replacedString);
        try {        
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /*
        donorPasswordResetEmail function Once clicked on URL from the email.Will send Redirect URL to let user to enter Password and Confirm Password.
    */     
    public function donorPasswordResetEmail(Request $request, $id, $model){
        // Validate the token        
        $data = PasswordResetToken::where('token', $request->secret_token)->first();
        if($data){  
            return success('donors.index', ['data' => [                    
                'data' => $data,
                'email' => $request->email,
                'secret_token' => $request->secret_token,
                'status' => true, 
                'redirect_url' => url('/') . '/users/newPassword/?secret_token=' . $data->token . '&email=' . urlencode($data->email),
                'message' =>'Password Reset Token Verified.',
                'statusCode' => 200,
            ]]);
        }else{
            return error('donors.index', ['data' => [                    
                'data' => null,
                'status' => false, 
                'message' =>'Password Reset Token Not Verified.',
                'statusCode' => 401,
            ]]);
        }
        
    }

    /*
        donorNewPassword function verify token and update user password. At the end Delete password reset token from db.
    */ 
    public function donorNewPassword(Request $request, $id, $model){
        //Validate input        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 
                            Password::min(8), 
                            'same:confirm_password'], 
            'confirm_password' => 'required',
            'secret_token' => 'required' 
        ]);
        // validateAll($validator);
        $password = $request->password;
        // Validate the token
        $tokenData = PasswordResetToken::where('token', $request->secret_token)->first();
        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData){            
            return error('donors.index', ['data' => [                    
                'data' => null,
                'status' => false, 
                'message' =>'Reset Token Not Verified.',
                'statusCode' => 401,
            ]]);        
        }
        $user = User::where('email', $tokenData->email)->first();
        if (!$user){
            return error('donors.index', ['data' => [                    
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
            return success('donors.index', ['data' => [                    
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

}
