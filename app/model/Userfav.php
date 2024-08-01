<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Userfav extends Model
{
    protected $fillable=[
           
        'user_id',
        'book_id',
      
    ];
}
