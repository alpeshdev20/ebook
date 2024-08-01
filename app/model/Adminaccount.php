<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Adminaccount extends Model
{
    protected $fillable=[
       
        'admin_name' ,
        'username' ,
        'email' ,
        'mobile' ,
        'password' ,
        'token' ,
    ];

    protected $hidden = [
        'password'
    ];
}
