<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // error_log('role');
        // error_log($role);
        
        // $user = auth('sanctum')->user();
        // error_log($user == null ? 'null': 'not null');
        // if ($user != null) {
        //     $roleName = $user->role->name;
        //     error_log($roleName);
        //     $isHasRole = $user->hasRole($role);
        //     error_log($isHasRole);
        //     if ($role->name == $roleName) {
        //         return true;
        //     }
        // }
        
        // error_log(print_r( $user, true ) );

        if(!auth('sanctum')->check() || auth('sanctum')->user()->role->name != $role)
        {
            error_log('redirect');
            // return redirect()->route('welcome');
            return response()->json([
                'message' => 'Access is denied',
            ], 403);
            // return redirect(RouteServiceProvider::HOME);
	    }
        
        // if (! auth()->user()->hasRole($role)) {
        //     return redirect()->route('welcome');
	    // }
        return $next($request);
    }
}
