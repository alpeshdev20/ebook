<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Rating;
use Validator;

class RatingController extends Controller
{
    public function index(){

        return Rating::all();
 
 
     }
 
     public function store(Request $request)
     {
        // return $request;
         $rules = [
              'user_id' => 'required',
         'book_id' => 'required',
         'rating' => 'required',
         'comment' => 'required',
       
          ]; 
 
         $error = Validator::make($request->all(),$rules);
 
         if($error->fails())
         {
             return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
         }
 
         $formdata = [
             'user_id' => $request->user_id,
             'book_id' => $request->book_id,
             'rating' => $request->rating,
             'comment' => $request->comment,
         
            
         ];
 
        // $schedule->save();
 
        Rating::create($formdata);
         return response()->json(['status'=>'200','message'=>'the rating is created Successfully']);
 
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         return response()->json(Rating::find($id));
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
        'rating' => 'required',
        'comment' => 'required',

         ]; 
 
        $error = Validator::make($request->all(),$rules);
 
        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
 
       
        $formdata = [
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        
        ];
 
 
         // $schedule->save();
 
         Rating::whereId($id)->update($formdata);
 
             return response()->json(['status'=>'200','message'=>'the rating is updated Successfully']);
         
         }
         
         
     
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         $data = Rating::find($id);
         if($data->delete())
         {
             return response()->json(['status'=>'200','message'=>'the rating is deleted successfully']);
         }else{
             return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
         }
 
 
         //
     }
 
     public function getRating()
     {
         return response()->json(Rating::all());
     }
 
}
