<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Globalnotification;
use Validator;

class GlobalnotificationController extends Controller
{
    public function index(){

        return Globalnotification::all();
 
 
     }
 
     public function store(Request $request)
     {
        // return $request;
         $rules = [
         'subject' => 'required',
         'message' => 'required',
         'image' => 'required',
            
          ]; 
          $file = $request->file('image');
 
         $error = Validator::make($request->all(),$rules);
 
         if($error->fails())
         {
             return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
         }
 
         if(!$file)
         {
             return response()->json(['status'=>'501','error'=>'Image is Required']);
         }
 
         $filename = $file->getClientOriginalName();
 
     
         
        
         $formdata = [
             'subject' => $request->subject,
             'message' => $request->message,
             'image' => $filename,
            
         ];
 
        // $schedule->save();
 
        Globalnotification::create($formdata);
        $file->move(public_path('image'),$filename);
         return response()->json(['status'=>'200','message'=>'the Global notification  is created Successfully']);
 
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         return response()->json(Globalnotification::find($id));
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
        'subject' => 'required',
        'message' => 'required',
        'image' => 'required',
           
         ]; 
         $file = $request->file('image');
 
        $error = Validator::make($request->all(),$rules);
 
        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
 
        if(!$file)
        {
            return response()->json(['status'=>'501','error'=>'Image is Required']);
        }
 
        $filename = $file->getClientOriginalName();
 
    
        
       
        $formdata = [
            'subject' => $request->subject,
            'message' => $request->message,
            'image' => $filename,
           
        ];
 
 
         // $schedule->save();
 
         Globalnotification::whereId($id)->update($formdata);
         $file->move(public_path('image'),$filename);
 
             return response()->json(['status'=>'200','message'=>'the Global notification is updated Successfully']);
         
         }
         
         
     
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         $data = Globalnotification::find($id);
         if($data->delete())
         {
             return response()->json(['status'=>'200','message'=>'the Global notification is deleted successfully']);
         }else{
             return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
         }
 
 
         //
     }
 
     public function getGlobalnotification()
     {
         return response()->json(Globalnotification::all());
     }
}
