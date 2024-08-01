<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacherdetail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('teacher_name');
            $table->string('mobile_no')->unique(); 
            $table->string('email')->unique();
            $table->string('institute_name');
            $table->string('department');
            $table->string('designation'); 
            $table->string('subject_taught');
            $table->string('resource_planning');
            $table->string('teaching_resource'); 
            $table->string('student_strength'); 
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
        Schema::dropIfExists('teacherdetail');
    }
}
