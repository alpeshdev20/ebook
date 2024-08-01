<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable=[
       
        'book_name' ,
        'book_image' ,
        'publisher_id' ,
        'year' ,
        // 'genre' ,
        'book_pdf' ,
        'price' ,
        'summary' ,
        'tags' , 
        'author' , 
    ];
    
}
