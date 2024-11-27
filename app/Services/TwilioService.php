<?php

namespace App\Services;

use Twilio\Rest\Client;


/**
 * Class TwilioService.
 */
class TwilioService
{

    public function index(){
        $sid    = env('TWILIO_ACCOUNT_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        $verification = $twilio->verify->v2->services("VA31a8d00ff9e7bbfefe4a014b374dde95")
                                        ->verifications
                                        ->create("+923456693379", "sms");

        print($verification->sid);
    }
}
