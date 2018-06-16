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


	/**
	 * Send verify email
	 * @param $user
	 * @return mixed
	 */
	public function sendVerifyEmail($user)
	{
		$this->createToken($user->id);
		return Mail::send(new EmailConfirmation($user));
	}
}