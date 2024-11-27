<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SendTwoFactorCode;
use App\Providers\RouteServiceProvider;
use App\Services\TwoFactorService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TwoFactorController extends Controller
{
    public $twoFactorService = null;
    public function __construct(TwoFactorService $twoFactorService){
        $this->twoFactorService = $twoFactorService;
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return $this->twoFactorService->twoFactorServiceIndex();        
    }

     /**
     * Is used to store two factor code for user in the table..
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
        return $this->twoFactorService->twoFactorServiceStore($request);
    }

     /**
     * Resend the code for two factor authentication.
     *
     * @return \Illuminate\Http\Response
     */

    public function resend(Request $request){               
        return $this->twoFactorService->twoFactorServiceResend($request);        
    }
}
