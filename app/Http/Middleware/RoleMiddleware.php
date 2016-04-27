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
    public function handle($request, Closure $next, $slug)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from token not found.');
            return response()->json(['error' => ['message' => 'User from token not found.']], 422);
        }

        $roleMatch = false;
        $roles = $user->getRoles();
        foreach ($roles as $role) {
            if ($role->slug == $slug) {
                $roleMatch = true;
                break;
            }
        }

        if ($roleMatch) {
            return $next($request);
        } else {
            return response()->json(['error' => ['message' => 'Not authorised as role slug:'.$slug]]);
        }
    }
}
