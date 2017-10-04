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
        return $result;
    }
}
