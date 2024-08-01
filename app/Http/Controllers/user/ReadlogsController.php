<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Readlogs;
use Validator;

class ReadlogsController extends Controller
{
    public function index()
    {
        return Readlogs::all();
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
            'user_id' => 'required',
            'book_id'  => 'required',
            'ip_address'  => 'required',
          
        ]; 
        
      
     
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
      
       
        $formdata = [
            'user_id' => $request->user_id,
            'book_id'  => $request->book_id,
            'ip_address'  => $request->ip_address,
            
        ];
       // $schedule->save();

       Readlogs::create($formdata);
     
       return response()->json(['status'=>'200','message'=>'the  readlogs is created Successfully'],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         // JWTAuth::parseToken()->authenticate();
        return response()->json(Readlogs::find($id));
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
        
        $rules = [
            'user_id' => 'required',
            'book_id'  => 'required',
            'ip_address'  => 'required',
           
        ]; 
       
     
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
     
       
        $formdata = [
            'user_id' => $request->user_id,
            'book_id'  => $request->book_id,
            'ip_address'  => $request->ip_address,
         
        ];
        Readlogs::whereId($id)->update($formdata);
       
        return response()->json(['status'=>'200','message'=>'the  Readlogs is Updated Successfully']);
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
        $data = Readlogs::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the Readlogs is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }
    }
    
}
