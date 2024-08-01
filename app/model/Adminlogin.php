<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Adminlogin extends Model
{
    protected $fillable= [
          'email' ,
          'password' 
    ];

    protected $hidden= [
           'password' 
  ];
}
