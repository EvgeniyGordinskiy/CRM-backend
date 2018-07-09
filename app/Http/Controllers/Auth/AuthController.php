<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\AuthService;
use App\Services\Verification\VerificationService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\AuthenticateRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
	/**
	 * This action will be fired when the user tries to authenticate.
	 *
	 * @param AuthenticateRequest $request
     * @param AuthService $authService
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
    public function authenticate(AuthenticateRequest $request, AuthService $authService)
    {
        $token = $authService->authenticate($request->email, $request->password);

        if (!$token) {
            return $this->respondUnauthorized('Invalid credentials', 40101);
        }
        return $this->respond(compact('token'));
    }

    /**
     * The action to register a user.
     *
     * @param RegisterRequest $request The incoming request with data.
     * @param AuthService $authService
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
    public function register(RegisterRequest $request,  AuthService $authService)
    {

        $token = $authService->register($request->first_name, $request->last_name, $request->timeZone, $request->email, $request->password);

        if ( !$token ) {
            return $this->respondUnauthorized('Error while registered user', 403);
        }

        return $this->respond(compact('token'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        VerificationService::setPlayload(['new_password' => bcrypt($request->password)]);
        $status = VerificationService::send(auth()->user());

        if( $status === VerificationService::SUCCESSFULLY_SEND ) return $this->respondWithSuccess('Verification email is sent.');

        return $this->respondWithError('Error while password changed.', 403);
    }
}
