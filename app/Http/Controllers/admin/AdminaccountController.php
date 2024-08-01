<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\model\Adminaccount;
use Validator;

class AdminaccountController extends Controller
{
    public function index()
    {
        return Adminaccount::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'admin_name'  => 'required',
            'username' => 'required|unique:adminaccounts',
            'email'  => 'required|email',
            'password'  => 'required',
            'mobile'  => 'required'
        ]; 


        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
       
              $formdata = [
                'admin_name' => $request->admin_name,
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password'  =>Hash::make($request->password),
                'token'  => Str::random(128),    
            ];
        
            Adminaccount::create($formdata);
            return response()->json(['message'=>'the Admin is created Successfully'],200); 

             
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

