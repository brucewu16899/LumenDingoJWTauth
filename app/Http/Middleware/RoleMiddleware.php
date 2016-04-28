<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from token not found.');
            return response()->json(['error' => ['message' => 'User from token not found.']], 422);
        }

        if ($user->inRole($role)) {
            return $next($request);
        } else {
            return response()->json(['error' => ['message' => 'Not authorised as role: '. $role]], 401);
        }
    }
}
