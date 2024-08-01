<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Adminlog extends Model
{
    protected $fillable=[
       
        'admin_id' ,
        'admin_name' ,
        'ip_address' ,
      
    ];
}
