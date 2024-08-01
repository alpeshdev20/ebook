<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Adminlog;
use Validator;

class AdminlogController extends Controller
{
    public function index()
    {
        return Adminlog::all();

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
            'admin_id'  => 'required',
            'admin_name'  => 'required',
            'ip_address' => 'required|unique:adminlogs',

        ]; 

        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
       
              $formdata = [
                'admin_id' => $request->admin_id,
                'admin_name' => $request->admin_name,
                'ip_address' => $request->ip_address,
             
            ];
        
            Adminlog::create($formdata);
            return response()->json(['message'=>'the Adminlog is created Successfully'],200); 

             
       
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

