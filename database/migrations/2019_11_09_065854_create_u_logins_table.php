<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateULoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $fillable= [
        'name',
        'email' ,
        'password',
        'mobile', 
  ];

    protected $hidden= [
            'password' 
    ];
    public function up()
    {
        Schema::create('u_logins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('mobile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_logins');
    }
}
