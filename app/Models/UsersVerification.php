<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

    class UsersVerification extends Model
{
    public function users(){
        $this->belongsTo(User::class);
    }
}
