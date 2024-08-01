<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription_plan extends Model
{
    //
    public $fillable =[
        'name',
        'price',
        'description',
        'validity',
        'status',
    ];
}
