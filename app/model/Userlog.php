<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Userlog extends Model
{
    protected $fillable=[
           
        'user_id',
        'ip_address',
      
    ];
}
