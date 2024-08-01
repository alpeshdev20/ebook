<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Completedread extends Model
{
    protected $fillable=[
           
        'user_id',
        'book_id',
        'date',
      
    ];
}
