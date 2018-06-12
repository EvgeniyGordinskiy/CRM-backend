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


    public function testRegister()
    {
        $data = $this->getUserData();
        $data['password'] = $this->getDefaultPassword();
        $data['password_confirmation'] = $this->getDefaultPassword();
        $resp = (array) json_decode(
            $this->json('POST', '/api/' . self::API_V1 . '/auth/register', $data )->content()
        );

        if(array_key_exists('token', $resp)) {
            if( ! $user = User::whereEmail($this->getDefaultEmail())->first() ) {
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
        $user = new User(['name' => 'test', 'email' => 'email@email.com']);
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
        if( ! $user = User::whereEmail($this->getDefaultEmail())->first() ) {
            $data = $this->getUserData();
            $data['password'] = $this->getDefaultPassword(true);
            $user = new User($data);
            $user->save();
        } else {
            throw new \Exception('Test user is present');
        }
        $playload = [
            'email' => $this->getDefaultEmail(),
            'password' => $this->getDefaultPassword()
        ];

        $resp = (array) json_decode(
            $this->json('POST', '/api/' . self::API_V1 . '/auth', $playload)->content()
        );

        if(array_key_exists('token', $resp)) $user->delete();
        $this->assertArrayHasKey('token', $resp);
    }

    private function getUserData()
    {
        return [
            'name' => 'test',
            'email' => $this->getDefaultEmail(),
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'token' => str_random(30),
            'timeZone' => 'en',
        ];
    }

    private function getDefaultEmail() : string
    {
        return 'httpTest@httpTest.com';
    }

    private function getDefaultPassword($crypt = false) : string
    {
        return $crypt ? bcrypt('111111') : '111111';
    }
}
