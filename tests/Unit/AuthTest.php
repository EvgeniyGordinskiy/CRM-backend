<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Test authenticate with wrong email.
     *
     * @return void
     */
    public function testAuthFailInvalidEmail()
    {
        $user = new User(['name' => 'test', 'email' => 'email']);
        $playload = [
            'email' => $user->email,
            'password' => 'secret'
        ];
        $this
            ->actingAs($user)
            ->json('POST', '/api/' . self::API_V1 . '/auth', $playload)
            ->assertStatus(422)
            ->assertJson([
                'email' =>  ["The email must be a valid email address."]
            ]);
    }

    public function testAuthFailWithInvalidPassword()
    {
        $user = new User(['name' => 'test', 'email' => 'email@email.com']);
        $playload = [
            'email' => $user->email,
            'password' => 'fg'
        ];
        $this
            ->actingAs($user)
            ->json('POST', '/api/' . self::API_V1 . '/auth', $playload)
            ->assertStatus(422)
            ->assertJson([
                'password' =>  ["The password must be at least 3 characters."]
            ]);
    }
}
