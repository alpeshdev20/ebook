<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\model\Completedread;
use Validator;

class CompletedreadController extends Controller
{
    public function index(){

        return Completedread::all();
 
 
     }
 
     public function store(Request $request)
     {
        // return $request;
         $rules = [
              'user_id' => 'required',
         'book_id' => 'required',
         'date' => 'required',
       
          ]; 
 
         $error = Validator::make($request->all(),$rules);
 
         if($error->fails())
         {
             return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
         }
 
         $formdata = [
             'user_id' => $request->user_id,
             'book_id' => $request->book_id,
             'date' => $request->date,
         
            
         ];
 
        // $schedule->save();
 
        Completedread::create($formdata);
         return response()->json(['status'=>'200','message'=>'the Completed read book is created Successfully']);
 
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         return response()->json(Completedread::find($id));
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
       'book_id' => 'required',
       'date' => 'required',
     
        ]; 

       $error = Validator::make($request->all(),$rules);

       if($error->fails())
       {
           return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
       }

       $formdata = [
           'user_id' => $request->user_id,
           'book_id' => $request->book_id,
           'date' => $request->date,
       
          
       ];

         Completedread::whereId($id)->update($formdata);
 
             return response()->json(['status'=>'200','message'=>'the Completed read book is updated Successfully']);
         
         }
         
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         $data = Completedread::find($id);
         if($data->delete())
         {
             return response()->json(['status'=>'200','message'=>'the Completed read book is deleted successfully']);
         }else{
             return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
         }
 
 
         //
     }
 
     public function getCompletedread()
     {
         return response()->json(Completedread::all());
     }
}
