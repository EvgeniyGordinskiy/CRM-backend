<?php

namespace App\Services\Verification\Handlers;

use App\Contracts\VerificationHandler;
use App\Mail\EmailConfirmation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationHandler implements VerificationHandler
{

    /**
     * Send verify email
     * @param User $user
     * @param String $string
     * @return boolean
     */
    public function send(User $user, String $string) : bool
    {
        Mail::send(new EmailConfirmation($user, $string));
        return (boolean) !Mail::failures();
    }
}