<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublisherRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publisher_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('upload_address_proof');
            $table->string('pan_card');
            $table->string('aadhar_card');
            $table->string('gst_or_tin_card');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('select_question');
            $table->string('security_answer');
            $table->string('check_box');
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
        Schema::dropIfExists('publisher_registrations');
    }
}
