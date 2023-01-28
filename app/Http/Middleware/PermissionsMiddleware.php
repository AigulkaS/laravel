<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permissions_array)
    {
        if(!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Access is denied',
            ], 403);
        } else {
            foreach($permissions_array as $permission){
                if(auth('sanctum')->user()->role->hasPermission($permission)) {
                    return $next($request);
                }
            }
            return response()->json([
                'message' => 'Access is denied',
            ], 403);
        }
    }
}
