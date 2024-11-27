<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $user = Auth::user();
        // Check if the user is authenticated and two-factor is required but not verified
        if ($user && !$user->two_factor_code && !$user->two_factor_verified) {
            // Redirect the user to the two-factor verification page
            return response()->json(['Message' => 'Cannot access without completing login']);
        }
        // If two-factor is verified or not required, continue with the request
        return $next($request);
    }
}
