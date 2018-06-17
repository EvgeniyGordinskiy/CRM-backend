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
    public function authenticate($email, $password)
    {
       $token = JWTAuth::attempt(['email' => $email, 'password' => $password]);

       return $token;
    }

    /**
     * Register user
     * @param $firstName
     * @param $lastName
     * @param $timeZone
     * @param $email
     * @param $password
     * @return mixed
     */
    public function register($firstName, $lastName, $timeZone, $email, $password)
    {
        $user = new User(
            [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'timeZone'   => $timeZone,
                'email'      => $email,
                'password'   => $password,
            ]
        );
        $user->password = bcrypt($user->password);

        $user->token = str_random(30);

        $user->save();

        $credentials = ['email' => $email, 'password' => $password];

        $token = JWTAuth::attempt($credentials);

        return $token;
    }

}