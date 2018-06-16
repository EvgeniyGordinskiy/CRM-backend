<?php

namespace App\Services\Verification;

use App\Contracts\VerificationHandler;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class VerificationService
{
    /**
     * Life time token
     * @var int
     */
    private static $expiration_time = 5;

    /**
     * Default verification handler
     * @var string
     */
    private static $defaultHandler = 'App\Services\Verification\Handlers\EmailVerificationHandler';

    /**
     * Current verification handler
     * @var null
     */
    private static $currentHandler = null;

    /**
     * Class which called Verification Service
     * @var string
     */
    private static $callingClass = '';

    public static function send(VerificationHandler $handler = null, User $user)
    {
        self::$currentHandler = $handler ?? new self::$defaultHandler();
        self::$currentHandler->send($user);
    }

    /**
     * Check token
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function checkToken($token)
    {
        if(
            !Cache::has($token)
            || !EmailConfirmation::whereUserId(Cache::get($token))->first()
        ){
            return redirect(env('APP_FRONT_URL').'/login');
        }else{
            return redirect(env('APP_FRONT_URL').'/home');
        }
    }

    /**
     * Create token
     * @param $id
     */
    private static function createToken($id)
    {
        $token = sha1(time());
        $expiresAt = Carbon::now()
            ->addMinutes(self::$expiration_time);
        Cache::put($token, $id, $expiresAt);
        new \App\EmailConfirmation([
            'user_id' => $id,
            'token' => $token
        ]);
    }
}