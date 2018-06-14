<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsersAuthSocial;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthSocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handle($provider)
    {
        $authUser = Socialite::driver($provider)->user();

        $user = $this->findOrCreateUser($authUser, $provider);

        $token = JWTAuth::attempt(['email' => $user->email, 'password' => bcrypt($user->social->provider_id)]);

        return $this->respond(compact('token'));
    }

    public function findOrCreateUser($authUser, $provider)
    {
        $user = User::whereEmail($authUser->email)->first();
        if(!$user) {
            $user = User::create([
                'name'     => $user->name,
                'email'    => $user->email,
                'token'   => str_random(30)
            ]);
        }

        $userAuthSocial = UsersAuthSocial::whereUserId($user->id)->whereProviderId($authUser->id)->first();
        if(!$userAuthSocial) {
            UsersAuthSocial::make([
               'user_id' => $user->id,
               'provider_name' => $provider,
               'provider_id' =>  $authUser->id,
            ]);
        }

        return $user;
    }
}
