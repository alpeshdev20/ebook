<?php

namespace App\model;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ULogin extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'u_logins';

    protected $fillable= [
        'name',
        'email' ,
        'password',
        'mobile', 
  ];

    protected $hidden= [
            'password' 
    ];
}
