<?php
namespace Tests;

use App\Models\User;

class TestUser
{
    public static function create_user($cryptPassword = true) : User
    {
        if( $user = User::whereEmail(TestUser::getDefaultEmail())->first() ) return $user;
        $data = self::getUserData();
        $data['password'] = TestUser::getDefaultPassword($cryptPassword);
        $user = new User($data);
        $user->save();
        return $user;
    }


    public static function getUserData()
    {
        return [
            'name' => 'test',
            'email' => self::getDefaultEmail(),
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'token' => str_random(30),
            'timeZone' => 'en',
        ];
    }

    public static function getDefaultEmail() : string
    {
        return 'httpTest@httpTest.com';
    }

    public static function getDefaultPassword($crypt = false) : string
    {
        return $crypt ? bcrypt('111111') : '111111';
    }
}