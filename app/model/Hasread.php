<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Hasread extends Model
{
    protected $fillable=[
           
        'user_id',
        'book_id',
        'page_number',
    ];
}
