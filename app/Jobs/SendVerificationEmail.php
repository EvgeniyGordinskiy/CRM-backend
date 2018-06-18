<?php

namespace App\Jobs;

use App\Mail\EmailConfirmation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;


class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @return void
     * @param  User $user
     * @param  String $string
     */
    public function __construct(User $user, String $string)
    {
       $this->user = $user;
       $this->token = $string;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new EmailConfirmation($this->user, $this->token));
    }
}
