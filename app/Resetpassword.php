<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resetpassword extends Model
{
    protected $fillable=[
        'user_id',
        'email',
        'token',
        'used',

    ];
}
