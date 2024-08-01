<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable=[
           
        'image',
        'heading',
        'description',
      
    ];
}
