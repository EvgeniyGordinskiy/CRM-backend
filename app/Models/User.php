<?php

namespace App\Models;

use App\EmailConfirmation;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, CanResetPassword
{
    use Notifiable;

    /**
     * @var string[] The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'timeZone',
        'password',
        'token',
    ];

    /**
     * @var string[] The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return mixed
     */
    public function social()
    {
        return $this->hasMany(UsersAuthSocial::class);
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Eloquent model method
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
            ]
        ];
    }

    public function userPermissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }
    
    public function emailVerifications()
    {
        $this->hasOne(EmailConfirmation::class);
    }
    
    public function getTokenProperty()
    {
        return $this->emailVerifications()->token;
    }
}