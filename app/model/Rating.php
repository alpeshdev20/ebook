<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable=[
           
        'user_id',
        'book_id',
        'rating',
        'comment',
      
      
    ];
}
