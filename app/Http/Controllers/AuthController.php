<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
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
	 * @param AuthenticateRequest $request
     * @uses
     *  POST auth/
     *              {
     *              email: string, valid email of the user,
     *              password: string, password of thr user
     *              }
     *
	 * @return \Illuminate\Http\JsonResponse
     *   Response body     
     *     {
     *       token: $token
     *     }
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
     * The action to register a user.
     *
     * @param RegisterRequest $request The incoming request with data.
     * @uses
     *  auth/register 
     *             {
     *              first_name: string, required, first name of the user,
     *              last_name: string, required, last name of the user,
     *              timeZone: string, required, current users time Zone,
     *              email: string, required, valid suers email,
     *              password: string, required, users password.
     *              confirm_password: string, required.
     *             }
     *
     * @return JsonResponse The JSON response if the user was registered.
     *   Response body
     *     {
     *       token: $token
     *     }
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

        return $this->respond(compact('token'));
    }

    public function confirmEmail(string $token)
    {

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
