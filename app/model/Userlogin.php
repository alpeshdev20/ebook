<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Userlogin extends Model
{
    protected $fillable=[
       
        'user_id' ,
        'ip_address' ,
        'status' ,
    ];
}
