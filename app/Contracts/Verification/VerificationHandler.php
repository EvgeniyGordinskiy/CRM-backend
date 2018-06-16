<?php

namespace App\Contracts;

use App\Models\User;

interface VerificationHandler
{
    /**
     * Send verification message to the user
     * @param User $user
     * @return mixed
     */
    public function send(User $user);
}