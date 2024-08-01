<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\model\Notification;
use Validator;

class NotificationController extends Controller
{
    public function index(){

       return Notification::all();


    }

    public function store(Request $request)
    {
       // return $request;
        $rules = [
             'user_id' => 'required',
        'subject' => 'required',
        'message' => 'required',
        'target' => 'required',
        'has_read' => 'required',
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
            'user_id' => $request->user_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'target' => $request->target,
            'has_read' => $request->has_read,
            'image' => $filename,
           
        ];

       // $schedule->save();

       Notification::create($formdata);
       $file->move(public_path('image'),$filename);
        return response()->json(['status'=>'200','message'=>'the Notification is created Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Notification::find($id));
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
       'subject' => 'required',
       'message' => 'required',
       'target' => 'required',
       'image' => 'required',
       'has_read' => 'required',
          
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
           'user_id' => $request->user_id,
           'subject' => $request->subject,
           'message' => $request->message,
           'target' => $request->target,
           'has_read' => $request->has_read,
           'image' => $filename,
          
       ];


        // $schedule->save();

        Notification::whereId($id)->update($formdata);
        $file->move(public_path('image'),$filename);

            return response()->json(['status'=>'200','message'=>'the Notification is updated Successfully']);
        
        }
        
        
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Notification::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the Notification is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }


        //
    }

    public function getNotification()
    {
        return response()->json(Notification::all());
    }

}
