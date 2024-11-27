<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    /*
        This permissions Middleware sis responsible for verifying permissions 
        against the current user and redirecting them back to the application
        by verifying the permissions information.
    */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if(Auth::check()) {

            $chunks = explode("\\", Route::currentRouteAction());
            $controller = end($chunks);

            $user = auth()->user();

            if(!$user->hasRole('Super Admin'))
            {
                $roles = DB::table('model_has_roles')->select('role_id')->where('model_id', '=', $user->id)->get();

                $roleArr = array();
                foreach ($roles as $value) {
                    $roleArr[] = $value->role_id;
                }

                $user_permissions = DB::table('permissions')
                        ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                        ->select('permissions.id')
                        ->where('permissions.action', 'LIKE',  "%{$controller}")
                        ->where('model_has_permissions.model_id', $user->id)
                        ->where('model_has_permissions.deny', '=', 0)
                        ->get();

                $user_deny_permission = DB::table('permissions')
                        ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                        ->select('permissions.id')
                        ->where('permissions.action', 'LIKE',  "%{$controller}")
                        ->where('model_has_permissions.model_id', $user->id)
                        ->where('model_has_permissions.deny', '=', 1)
                        ->count();

                $hasUserPermission = count($user_permissions);


                $role_permissions = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->select('permissions.id')
                        ->where('permissions.action', 'LIKE',  "%{$controller}")
                        ->whereIn('role_has_permissions.role_id', $roleArr)
                        ->get();

                $hasRolePermission = count($role_permissions);


                if($hasRolePermission == 0 && $hasUserPermission == 0) {
                    if(request()->headers->get('referer')) {
                        return redirect()->back()->withErrors(['Permission  not allowed.']);
                    } else {
                        abort(403, $message = __('Permission  not allowed.'));
                    }
                } 

                if($hasRolePermission > 0) {

                    if($user_deny_permission > 0) {
                        
                        if(request()->headers->get('referer')) {
                            return redirect()->back()->withErrors(['Permission  not allowed.']);
                        } else {
                            abort(403, $message = __('Permission  not allowed.'));
                        }
                    }

                }
            }
        }
        return $next($request);
    }
}
