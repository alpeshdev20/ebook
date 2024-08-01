<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class AppAd extends Model
{
    protected $fillable=[
       
        'image' ,
        'material' ,
        'active'
    ];
}
