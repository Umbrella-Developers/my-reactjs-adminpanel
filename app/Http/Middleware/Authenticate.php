<?php

namespace App\Http\Middleware;

use Exception;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string

    {
        if (Auth::check()) {
            return view('login');
        }elseif($request->getRequestUri() == '/users/verifyEmail'){
            return route('verifyEmail');
        }
        return $request->expectsJson() ? null : 'login';       
    } 
}
