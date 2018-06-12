<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AuthTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Test register route.
     * @throws \Exception
     */
    public function testRegister()
    {
        $data = TestUser::getUserData();
        $data['password'] = TestUser::getDefaultPassword();
        $data['password_confirmation'] = TestUser::getDefaultPassword();
        $resp = (array) json_decode(
            $this->json('POST', '/api/' . self::API_V1 . '/auth/register', $data )->content()
        );

        if(array_key_exists('token', $resp)) {
            if( ! $user = User::whereEmail(TestUser::getDefaultEmail())->first() ) {
                throw new \Exception('Test user is not present');
            }
            $user->delete();
        }

        $this->assertArrayHasKey('token', $resp);
    }

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

    /**
     *  Test authenticate with min value for password.
     */
    public function testAuthFailWithInvalidPassword()
    {
        $user = new User(['name' => 'test', 'email' => 'email@email.com']);
        $playload = [
            'email' => $user->email,
            'password' => 'fg'
        ];
        $this
            ->json('POST', '/api/' . self::API_V1 . '/auth', $playload)
            ->assertStatus(422)
            ->assertJson([
                'password' =>  ["The password must be at least 3 characters."]
            ]);
    }

    /**
     *  Test authenticate with empty password.
     */
    public function testAuthFailRequiredPassword()
    {
        $user = new User(['name' => 'test', 'email' => 'email@email.com']);
        $playload = [
            'email' => $user->email,
            'password' => ''
        ];
        $this
            ->json('POST', '/api/' . self::API_V1 . '/auth', $playload)
            ->assertStatus(422)
            ->assertJson([
                'password' =>  ["The password field is required."]
            ]);
    }

    /**
     *  Test authenticate with empty email.
     */
    public function testAuthFailRequiredEmail()
    {
        $playload = [
            'email' => '',
            'password' => 'ljll'
        ];
        $this
            ->json('POST', '/api/' . self::API_V1 . '/auth', $playload)
            ->assertStatus(422)
            ->assertJson([
                'email' =>  ["The email field is required."]
            ]);
    }

    /**
     *  Test authenticate successfully.
     */
    public function testAuthSuccessfully()
    {
        if( ! $user = User::whereEmail(TestUser::getDefaultEmail())->first() ) {
            $user = TestUser::create_user();
        } else {
            throw new \Exception('Test user is present');
        }
        $playload = [
            'email' => TestUser::getDefaultEmail(),
            'password' => TestUser::getDefaultPassword()
        ];

        $resp = (array) json_decode(
            $this->json('POST', '/api/' . self::API_V1 . '/auth', $playload)->content()
        );

        if(array_key_exists('token', $resp)) $user->delete();
        $this->assertArrayHasKey('token', $resp);
    }
}
