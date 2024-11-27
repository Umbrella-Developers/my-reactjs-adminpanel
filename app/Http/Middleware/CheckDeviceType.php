<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDeviceType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->header('User-Agent');

        if ($this->isWebBrowser($userAgent)) {
            $request->attributes->set('device_type', 'web');
        } else {
            $request->attributes->set('device_type', 'web');
        }

        return $next($request);
    }

    /**
     * Determine if the User-Agent corresponds to a web browser.
     *
     * @param  string  $userAgent
     * @return bool
     */
    protected function isWebBrowser($userAgent)
    {
        $browsers = [
            'Mozilla', 'Chrome', 'Safari', 'Opera', 'MSIE', 'Trident'
        ];

        foreach ($browsers as $browser) {
            if (stripos($userAgent, $browser) !== false) {
                return true;
            }
        }

        return false;
    }
}
