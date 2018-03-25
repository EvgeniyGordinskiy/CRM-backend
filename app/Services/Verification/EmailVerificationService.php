<?php

namespace App\Services\Verification;

use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;

class EmailVerificationService
{
	public function sendVerifyEmail($user)
	{
		return Mail::send(new EmailConfirmation($user));
	}
}