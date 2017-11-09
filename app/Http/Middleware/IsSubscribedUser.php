<?php

namespace App\Http\Middleware;

use Closure;

class IsSubscribedUser extends VerifyJWTToken
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
        $result = parent::handle($request, $next);
        $token = $this->auth->setRequest($request)->getToken();
        $user = $this->auth->authenticate($token);
        $currentRoute = $request->route()->getName();
        $userPermissions = $user->userPermissions()->get()->contains(function ($item) use ($currentRoute) {
            return $item->name === $currentRoute;
        });

        if ($userPermissions){
            return $result;
        }

        return $this->respondUnauthorized(
            'User has no system access',
            40118
        );
    }
}
