<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\TeacherDetail; 
use DB;
use Vaidator; 

class TeacherDetails extends Controller
{
   
    public function save_teacher_detail(Request $request)
      {
        $teacher_name = $request->teacher_name ;
        $mobile_no = $request->mobile_no; 
        $email = $request->email; 
        $inst_name = $request->institute_name ; 
        $department = $request->department; 
        $designation = $request->designation; 
        $sub_taught = $request->subject_taught ; 
        $resource_planing = $request->resource_planning; 
        $teaching_resource = $request->teaching_resource; 
        $student_strength = $request->student_strength ; 

       $data = TeacherDetail::where('email',$email)->orWhere('mobile_no',$mobile_no)->first();
       
       if($data)

       {
        return response()->json(['status'=>'500','message'=>'Mobile and email is already exist','success'=>false]);
      
       }

        $insert = TeacherDetail::insert([
            'teacher_name'=> $teacher_name,
            'mobile_no'=> $mobile_no,
            'email'=>$email,
            'institute_name'=>$inst_name,
            'department'=>$department,
            'designation'=>$designation,
            'subject_taught'=>$sub_taught,
            'resource_planning'=>$resource_planing,
            'teaching_resource'=>$resource_planing,
            'student_strength'=>$student_strength
        ]);


        if($insert)
        {
            return response()->json(['success'=>true,'status'=>'200','message'=>'your data is successfully inserted']);
      
        }


        
    }
}
