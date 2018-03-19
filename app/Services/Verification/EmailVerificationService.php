<?php

namespace app\Services\Permission;

use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;

class EmailVerificationService
{
	public function sendVerifyEmail($user)
	{
		return Mail::send(new EmailConfirmation($user));
	}
}