<?php

namespace App\Services\Verification;

use App\Mail\EmailConfirmation;
use App\Models\User;
use App\Traits\Restable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class EmailVerificationService
{
	use Restable;

	private $expiration_time = 5;
	
	public function sendVerifyEmail($user)
	{
		$this->createToken($user->id);
		return Mail::send(new EmailConfirmation($user));
	}

	public function checkToken($token)
	{
		if(
			!Cache::has($token)
			|| !EmailConfirmation::whereUserId(Cache::get($token))->first()
		){
			return redirect(env('APP_FRONT_URL').'/login', 302);
		}else{
			return redirect(env('APP_FRONT_URL').'/home', 200);
		}
	}

	private function createToken($id)
	{
		$token = sha1(time());
		$expiresAt = Carbon::now()
			->addMinutes($this->expiration_time);
		Cache::put($token, $id, $expiresAt);
		new \App\EmailConfirmation([
			'user_id' => $id,
			'token' => $token
		]);
	}
}