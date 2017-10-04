<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\AuthenticateRequest;
use Hash;

class AuthController extends BaseController
{
    /**
     * This action will be fired when the user tries to authenticate.
     *
     * @param AuthenticateRequest $request The request for authentication.
     *
     * @return JsonResponse The token in a JSON format.
     */
    public function authenticate(AuthenticateRequest $request)
    {

        $token = JWTAuth::attempt($this->getCredentials($request));

        if (!$token) {
            return $this->respondUnauthorized('Invalid credentials', 40101);
        }

        return $this->respond(compact('token'));
    }


    /**
     * Return the credential that are mandatory.
     *
     * @param  AuthenticateRequest $request The request for authentication.
     * @return Array The credentials.
     */
    public function getCredentials(AuthenticateRequest $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
    }


    /**
     * The action to register a user.
     *
     * @param RegisterRequest $request The incoming request with data.
     *
     * @return JsonResponse The JSON response if the user was registered.
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

        $app = app();
        $routes = $app->routes->getRoutes();

        return $this->respond(compact('token'));
    }

}
