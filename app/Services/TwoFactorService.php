<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Http\Request;

/**
 * Class TwoFactorService.
 */
class TwoFactorService
{
    public function twoFactorServiceIndex(){
        return success('all.index', ['data' => [                    
            'message' => 'Displaying the verification form ',
            'URL' => url('/') . '/verify',
            'statusCode' => 200,
        ]]);
    }
    
    public function twoFactorServiceShow(){

    }

    public function twoFactorServiceStore(Request $request){
        $rules = [
            'two_factor_code' => ['integer', 'required'],
        ];
    
        // Validate the request using the helper function
        validateRequest($request, $rules); 
        $user = User::find($request->user_id);
        if ($request->input('two_factor_code') !== $user->two_factor_code) {       
            return error('all.index', ['data' => [                    
                'status' => false,
                'message' =>'Invalid code provided',
                'statusCode' => 401,
            ]]);  
        }
        $user->resetTwoFactorCode();

        return success('all.index', ['data' => [                    
            'status' => true,
            'message' => 'Logged in successfully',
            'statusCode' => 200,
        ]]);
    }

    public function twoFactorServiceResend(Request $request){
        $user = User::find($request->user_id);
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCode());
        return success('all.index', ['data' => [                    
            'status' => true,
            'message' => 'Code resent successfully',
            'statusCode' => 200,
        ]]);
    }
}
