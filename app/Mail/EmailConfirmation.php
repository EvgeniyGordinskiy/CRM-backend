<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $token;

    /**
     * EmailConfirmation constructor.
     * @param \App\Models\User $user
     * @param string $token
     */
    public function __construct(\App\Models\User $user, String $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = route('password.change', ['token' => $this->token]);
        return $this
            ->to($this->user->email)
            ->markdown('emails.confirmation')
            ->with(['url' => $url]);
    }

}
