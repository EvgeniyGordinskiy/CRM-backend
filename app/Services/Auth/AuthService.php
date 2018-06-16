<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\AuthenticateRequest;

class AuthService
{
    /**
     *  Authenticate user
     * @param AuthenticateRequest $request
     * @return mixed
     */
    public function authenticate(AuthenticateRequest $request)
    {
       $token = JWTAuth::attempt($this->getCredentials($request));

       return $token;
    }

    /**
     * Register user
     * @param RegisterRequest $request
     * @return mixed
     */
    public function register(RegisterRequest $request)
    {
        $user = new User($request->only(
            [
                'first_name',
                'last_name',
                'timeZone',
                'email',
                'password',
            ]
        ));
        $user->password = bcrypt($user->password);

        $user->token = str_random(30);

        $user->save();

        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);

        return $token;
    }

    /**
     * Return the credential that are mandatory.
     *
     * @param  AuthenticateRequest $request The request for authentication.
     * @return array The credentials.
     */
    private function getCredentials(AuthenticateRequest $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
    }


}