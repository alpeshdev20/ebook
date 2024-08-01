<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Bookread extends Model
{
    protected $fillable=[
           
        'user_id',
        'book_id',
        'page_no',
        'duration',
        'start_date',
        'end_date',
    ];
}
