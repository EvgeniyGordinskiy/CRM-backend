<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UsersVerification extends Model
{
    protected $table = 'users_verification';
    protected $guarded = [];
    public function users(){
        $this->belongsTo(User::class);
    }
}
