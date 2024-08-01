<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Useraddress extends Model
{
    protected $fillable=[
           
        'user_id',
        'address',
        'district',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',

    ];
}
