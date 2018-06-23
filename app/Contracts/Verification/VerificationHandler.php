<?php

namespace App\Contracts;

use App\Models\User;

interface VerificationHandler
{
    /**
     * Send verification message to the user
     * @param User $user
     * @param String $string
     */
    public function send(User $user, String $string) ;
}