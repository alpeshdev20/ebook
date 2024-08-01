<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Redglobalnotification;
use Validator;

class RedglobalnotiController extends Controller
{
    public function index(){

        return Redglobalnotification::all();
 
 
     }
 
     public function store(Request $request)
     {
        // return $request;
         $rules = [
         'user_id' => 'required',
         'golbalnotification_id' => 'required',
            
          ]; 
 
         $error = Validator::make($request->all(),$rules);
 
         if($error->fails())
         {
             return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
         }
 
         $formdata = [
             'user_id' => $request->user_id,
             'golbalnotification_id' => $request->golbalnotification_id,
            
         ];
 
        // $schedule->save();
 
        Redglobalnotification::create($formdata);
         return response()->json(['status'=>'200','message'=>'the red Global notification  is created Successfully']);
 
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         return response()->json(Redglobalnotification::find($id));
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
        'golbalnotification_id' => 'required',
           
         ]; 
 
        $error = Validator::make($request->all(),$rules);
 
        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
 
      
       
        $formdata = [
            'user_id' => $request->user_id,
            'golbalnotification_id' => $request->golbalnotification_id,
           
        ];
 
 
         // $schedule->save();
 
         Redglobalnotification::whereId($id)->update($formdata);
 
             return response()->json(['status'=>'200','message'=>'the red Global notification is updated Successfully']);
         
         }
         
         
     
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         $data = Redglobalnotification::find($id);
         if($data->delete())
         {
             return response()->json(['status'=>'200','message'=>'the  red Global notification is deleted successfully']);
         }else{
             return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
         }
 
 
         //
     }
 
     public function getRedglobalnotification()
     {
         return response()->json(Redglobalnotification::all());
     }
}
