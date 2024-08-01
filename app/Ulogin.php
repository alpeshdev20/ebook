<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Ulogin extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table = "u_logins";
    protected $fillable= [
        'name',
        'email' ,
        'password',
        'mobile', 
  ];

    protected $hidden= [
            'password' 
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }
}
