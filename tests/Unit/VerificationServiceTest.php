<?php

namespace Tests\Unit;

use App\Jobs\SendVerificationEmail;
use App\Services\Verification\VerificationService;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Tests\TestUser;

class VerificationServiceTest extends TestCase
{
    public function testSendSuccess()
    {
       Queue::fake();
        $status = VerificationService::send(TestUser::create_user());
        Queue::assertPushed(SendVerificationEmail::class, function ($job) use($status) {
            return $status === VerificationService::SUCCESSFULLY_SEND;
        });
    }
}