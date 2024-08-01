<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Forgetpassword extends Model
{
    protected $fillable=[
        'user_id',
        'mobile',
        'otp',

    ];
}
