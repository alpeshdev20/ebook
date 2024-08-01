<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Globalnotification extends Model
{
    protected $fillable=[
           
        'subject',
        'message',
        'image'
      
    ];
}
