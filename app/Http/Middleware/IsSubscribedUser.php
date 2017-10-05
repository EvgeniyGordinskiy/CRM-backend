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
        $curretnRoute = $request->route()->getName();
        $userPermissions = $user->userPermissions()->get()->contains(function ($item) use ($curretnRoute) {
            return $item->name === $curretnRoute;
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
