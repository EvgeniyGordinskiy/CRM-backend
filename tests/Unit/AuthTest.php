<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthWithFaile()
    {
        $user = factory(\App\Models\User::class,1)->create()[0];
        
        $playload = [
            'email' => $user->email,
            'password' => 'secret'
        ];
        $this
            ->actingAs($user)
            ->json('POST', '/api/' . self::API_V1 . '/auth', $playload)
            ->assertStatus(401);
    }
}
