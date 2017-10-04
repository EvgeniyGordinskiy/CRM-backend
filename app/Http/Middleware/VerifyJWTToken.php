<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use App\Traits\Restable;

class VerifyJWTToken extends Authenticate
{
    use Restable;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $result = parent::handle($request, $next);
        $token = $this->auth->setRequest($request)->getToken();
        $user = $this->auth->authenticate($token);
        $curretnRoute = $request->route()->getName();
        $userPermissions = $user->userPermissions()->get()->map(function ($item) {
           return $item->name;
        });

        if (!$curretnRoute || !in_array($curretnRoute, $userPermissions)){
            return $this->respondUnauthorized(
                'User has no system access',
                40118
            );
        }
        return $result;
    }
}
