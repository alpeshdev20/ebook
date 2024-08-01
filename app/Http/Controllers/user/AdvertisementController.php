<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Advertisement;
use Validator;

class AdvertisementController extends Controller
{
    public function index(){

       


    }

    public function store(Request $request)
    {
        //return $request;
        $rules = [
            'heading' => 'required',
        'description'=> 'required',
           
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
            'image' => $filename,
            'heading'=> $request->heading,
            'description'=>$request->description,
           
        ];

       // $schedule->save();

       Advertisement::create($formdata);
        $file->move(public_path('image'),$filename);
        
        return response()->json(['status'=>'200','message'=>'the Advertisement banner is created Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Advertisement::find($id));
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
            'heading' => 'required',
        'description'=> 'required',
           
         ]; 

        $file = $request->file('image');

        
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

        if($file)
        {
            $filename = $file->getClientOriginalName();
        
            $formdata = [
                'image' => $filename,
                'heading'=> $request->heading,
                'description'=>$request->description,
               
            ];

        // $schedule->save();

        Advertisement::whereId($id)->update($formdata);
            $file->move(public_path('image'),$filename);
            return response()->json(['status'=>'200','message'=>'the Advertisement banner is updated Successfully']);
        
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Advertisement::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the Advertisement banner is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }


        //
    }

    public function getAdvertisement()
    {
        return response()->json(Advertisement::all());
    }
}
