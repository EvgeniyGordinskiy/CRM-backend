<?php
namespace App\Http\Controllers;

use App\EmailConfirmation;
use App\Models\User;
use App\Services\Verification\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EmailConfirmationController extends Controller
{
    private $service;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->service = $emailVerificationService;
    }

    public function send()
    {
        $this->service->sendVerifyEmail(Auth::user());
    }

    public function confirm($token)
    {
        $this->service->checkToken($token);
    }
}
