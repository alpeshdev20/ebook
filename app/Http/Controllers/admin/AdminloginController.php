<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\model\Adminlogin;
use Validator;

class AdminloginController extends Controller
{
    public function login(Request $request)
    {
        $user = Adminlogin::get();
      //  return $request;
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'false', 'error' => $error->errors()->all()],500);
        }

        $email = $request->input('email');
        $password = $request->input('password');
    
//	$user = Adminlogin::all();
//	return $user;
         $user = Adminlogin::where('email', '=', $email)->get();

//	return $user;
	if(count($user) < 1) {
		 return response()->json(['success'=>'false', 'message' => 'Invalid Email']);
	}

         if (Hash::check($password, $user[0]->password)) {
            return response()->json(['success'=>'true','message'=>'success', 'data' => $user[0]]);
         }

            return response()->json(['success'=>'false', 'message' => 'Login Fail, Incorrect Email id And Password']);
         
       

        //  if (!$user) {
        //     return response()->json(['success'=>'false', 'message' => 'Login Fail, please check email']);
        //  }
        //  if (!Hash::check($password, $user->password)) {
        //     return response()->json(['success'=>'false', 'message' => 'Login Fail, please check password']);
        //  }
        //     return response()->json(['success'=>'true','message'=>'success', 'data' => $user]);
           
        
    }
    public function register(Request $request){

        $rules = [
            'email' => 'required|unique:adminlogins',
            'password' => 'required'
        ];

        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'false', 'error' => $error->errors()->all()],500);
        }

        $formdata = [
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        Adminlogin::create($formdata);        
        return response()->json(['success'=>'true', 'message' => 'Registration Successfully'],200);
    }


    
}

