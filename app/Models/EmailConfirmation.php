<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EmailConfirmation extends Model
{
    public function users(){
        $this->belongsTo(User::class);
    }
}
