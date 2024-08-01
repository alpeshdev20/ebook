<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Readlogs extends Model
{
    protected $fillable=[
           
        'user_id',
        'book_id',
        'ip_address',
      
    ];
}
