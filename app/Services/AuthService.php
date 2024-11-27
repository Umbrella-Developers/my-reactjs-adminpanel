<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\UserRegisterEmail;
use App\Models\Configuration;
use App\Models\Token;
use App\Notifications\SendTwoFactorCode;
use App\Providers\RouteServiceProvider;
use App\Services\UserService;
use App\Traits\ServiceTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use PhpParser\Node\Expr\FuncCall;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

/**
 * Class AuthService.
 */
class AuthService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;

    public function userCreate(){
        return view('users.create');
    }

    /*
        Storing Registration data of a user.
        Note. Service is being created for AuthController. 
        And logic is being handled in AuthService. And base structure is given in AuthController.
    */
    
    public function authStore(Request $request, $id, $model){    
        $requestArray = null;           
        //Validated
        $rules = [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone_number' => 'required|unique:users',
        ];
    
        // Validate the request using the helper function
        validateRequest($request, $rules);          
        $data = $this->store($request, $id, $model, $requestArray);
        $record = $model::where('email', $request->email)->first()->assignRole('Manager');
        // $this->sendEmail($request->email, $model);        
        // return $data;
        return success('users.index', ['data' => [                    
            strtolower($this->modelName)  => $record,
            'status' => true,
            'message' => $this->modelName . ' Created Successfully',
            'statusCode' => 200,
        ]]);
    }

    /*
        Logging in user which is being registered. With any associated role.
    */
    public function userLogin(Request $request){
        //Validated
        $rules = [
            'email' => 'required|',
            'password' => 'required'
        ];
    
        // Validate the request using the helper function
        validateRequest($request, $rules); 
    
        $user = User::where('email', $request->email)->first();
        if(!isset($user)){
            return redirect()->back()->withErrors(['email' => 'User does not exist.']);
        }
        if($user->is_active == 0){
            return redirect()->back()->withErrors(['email' => 'User is not active.']);
        }
        //if ($user && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $credentials = ['email' => $request->email, 'password' => $request->password];
        $validate = Auth::validate($credentials);
        if($validate) {
            // After successful login, redirect the user to the 2FA selection page
            // Initialize variables
            $method = '';
            $info_text = '';
            $masked_text = '';
            // Send 2FA code via email
            $user->generateTwoFactorCode();
            $full_name = $user->first_name . ' ' . $user->last_name;
            $patterns = [];
            $patterns = ['full_name' => $full_name, 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'code' => $user->two_factor_code];
            prepareEmailTemplate($user->email, 'two_factor_email', $patterns, $subject = 'Send Two Factor Code');
            // Set email-specific messages
            $info_text = 'We have sent an email with a 6-digit code to your email address.';
            $masked_text = maskEmail($user->email);

            $method = $request->two_factor_method;
    
            // Redirect to the 2FA verification page with necessary data
            return view('auth.two_factor_selection', [
                'user_id' => $user->id,
                'user' => $user,
                'method' => $method,
                'info_text' => $info_text,
                'masked_text' => $masked_text,
            ]);
            //return view('auth.two_factor_selection', ['user' => $user]);
        }
        else {
            return redirect()->back()->withErrors(['email' => 'Email & Password do not match our records.']);
        }
    }

    public function authenticated(){
    
    }

    /*
        Logout user from the system  
    */
    public function userLogout(Request $request){

        try {
            if(!Auth::check()){
                return redirect('login');
            }
            // Get the authenticated user
            $user = Auth::user();
    
            // Delete all tokens for the user (invalidate API tokens)
            $user->tokens()->delete();
    
            // Invalidate the session
            $request->session()->invalidate();
    
            // Regenerate session token to prevent fixation attacks
            $request->session()->regenerateToken();
    
            // Clear session-related cookies
            Cookie::queue(Cookie::forget('laravel_session'));
            Cookie::queue(Cookie::forget('XSRF-TOKEN'));
    
            // Log out the user
            Auth::logout();
    
            // Redirect to the login page
            return redirect('/login')->with('status', 'Logged out successfully');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Logout failed: ' . $e->getMessage());
        }
    }
    
    /*
        senEmail sends an email to new user registration email address.
    */
    public function sendEmail($email, $model){
        $user = $model::select('first_name', 'last_name', 'email')->where('email', $email)->first();
        $full_name = $user->first_name . ' ' . $user->last_name;
        $patterns = [];
        $patterns = ['full_name' => $full_name, 'first_name' => $user->first_name, 'last_name' => $user->last_name];
        prepareEmailTemplate($email, 'password_reset_success', $patterns);
    }

    public function authRedirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function authHandleGoogleCallback(Request $request, $id, $model){
        $data = null;
        $user = Socialite::driver('google')->user();
        $findUser = User::where('email', $user->getEmail())->first();        
        if ($findUser) {            
            Auth::login($findUser);
            $data = $findUser;
        } else {            
            $data = $model::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId(),
                'password' => Hash::make('password'),
                'phone_number' => '',
            ]);
            $model::where('email', $data->email)->first()->assignRole('Manager');
            Auth::login($data);
        }
        return $data;
    }

    public function authShow(){
        // Check if the user is already logged in
        if (Auth::check()) {
            // Redirect to the donations page if logged in
            return redirect('/donations');
        }
        return view('login');
    }

    public function sendTwoFactorCode(Request $request) {
        // Find the user by user_id
        $user = User::find($request->user_id);
    
        // Initialize variables
        $method = '';
        $info_text = '';
        $masked_text = '';
    
        // Check which method was selected for two-factor authentication
        if ($request->two_factor_method === 'email') {
            // Send 2FA code via email
            $user->generateTwoFactorCode();
            $full_name = $user->first_name . ' ' . $user->last_name;
            $patterns = [];
            $patterns = ['full_name' => $full_name, 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'code' => $user->two_factor_code];
            prepareEmailTemplate($user->email, 'two_factor_email', $patterns, $subject = 'Send Two Factor Code');
            // Set email-specific messages
            $info_text = 'We have sent an email with a 6-digit code to your email address.';
            $masked_text = maskEmail($user->email);
    
        } elseif ($request->two_factor_method === 'sms') {
            // Send 2FA code via SMS using Twilio
            try {
                // Ensure the user has a phone number
                if (!empty($user->phone_number)) {
                    // Ensure the phone number is in E.164 format
                    $formattedPhoneNumber = $this->formatPhoneNumber($user->phone_number);
    
                    // Log the phone number being sent for debugging
                    Log::info('Phone number being sent: ' . $formattedPhoneNumber);
    
                    // Generate and save the two-factor code
                    $user->generateTwoFactorCode();
    
                    // Initialize the Twilio client
                    $twilioClient = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
                    // Send SMS via Twilio
                    $twilioClient->messages->create(
                        $formattedPhoneNumber, // Phone number in E.164 format
                        [
                            'from' => env('TWILIO_PHONE_NUMBER'), // Your Twilio phone number from .env
                            'body' => 'Your two-factor authentication code is: ' . $user->two_factor_code,
                        ]
                    );
                    
                    // Set SMS-specific messages
                    $info_text = 'We have sent an SMS with a 6-digit code to your phone number.';
                    $masked_text = maskPhoneNumber($user->phone_number);
                    
                } else {
                    // Handle missing phone number
                    return back()->withErrors(['error' => 'User does not have a valid phone number.']);
                }
    
            } catch (\Exception $e) {
                // Log the error and show a friendly message to the user
                Log::error('Failed to send SMS: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Failed to send SMS. Please try again.']);
            }
        }
    
        // Set the selected method
        $method = $request->two_factor_method;
    
        // Redirect to the 2FA verification page with necessary data
        return view('auth.two_factor_selection', [
            'user_id' => $user->id,
            'user' => $user,
            'method' => $method,
            'info_text' => $info_text,
            'masked_text' => $masked_text,
        ]);
    }
    
    // Function to format phone numbers in E.164 format
    private function formatPhoneNumber($phoneNumber) {
        // Example implementation: Ensure the phone number starts with '+'
        if (substr($phoneNumber, 0, 1) !== '+') {
            // Assuming it's a local number, prepend the country code (e.g., '+1' for USA)
            // Adjust the country code as necessary for your region
            $phoneNumber = '+1' . $phoneNumber;
        }
        return $phoneNumber;
    }

    public function verifyTwoFactorCode(Request $request) {
        // Find the user by the ID
        $user = User::find($request->user_id);
    
        // Check if the two-factor code is correct and has not expired
        if ($user->two_factor_code === $request->code && !$user->two_factor_expires_at->isPast()) {
            // Reset the two-factor code after successful verification
            $user->resetTwoFactorCode();
    
            // Mark two-factor as verified
            $user->two_factor_verified = true;
            $user->save();
    
            // Continue with authentication process
            $roles = $user->getRoleNames();
            $permissions = $user->getAllPermissions()->pluck('name');
            $pagePermissions = [];
    
            foreach ($permissions as $permission) {
                $data = explode('-', $permission);
                if (!in_array($data[0], $pagePermissions)) {
                    $pagePermissions[] = $data[0];
                }
            }
            Auth::loginUsingId($user->id);
            // Handle AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Two-factor authentication successful.',
                    'redirect_url' => url('/donations')
                ]);
            }
        } else {
            // If the code is invalid or expired, handle error response
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The two-factor code is invalid or has expired.'
                ], 422);
            }
        }
    }
}