<?php

namespace App\Services\Verification\Handlers;

use App\Contracts\VerificationHandler;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationHandler implements VerificationHandler
{

    /**
     * Send verify email
     * @param $user
     * @return mixed
     */
    public function send(User $user)
    {
        return Mail::send(new EmailConfirmation($user));
    }
}