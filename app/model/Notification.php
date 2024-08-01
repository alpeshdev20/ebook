<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable=[
           
        'user_id',
        'subject',
        'message',
        'target',
        'has_read',
        'image'
      
    ];
}
