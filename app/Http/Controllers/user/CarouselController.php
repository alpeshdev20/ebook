<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Carousel;
use Validator;

class CarouselController extends Controller
{
    public function index(){

        return Carousel::all();


    }

    public function store(Request $request)
    {
        // return $request;
        // $rules = [
        //     'pic' => 'required',
           
        // ]; 

        $file = $request->file('banner_image');

        
        // $error = Validator::make($request->all(),$rules);

        // if($error->fails())
        // {
        //     return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        // }

        if(!$file)
        {
            return response()->json(['status'=>'501','error'=>'Image is Required']);
        }

        $filename = $file->getClientOriginalName();
        
       
        $formdata = [
            'banner_image' => $filename,
           
        ];

       // $schedule->save();

       Carousel::create($formdata);
        $file->move(public_path('image'),$filename);
        return response()->json(['status'=>'200','message'=>'the banner is created Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Carousel::find($id));
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
        // $rules = [
        //     'pic' => 'required',

        // ]; 

        $file = $request->file('banner_image');

        
        // $error = Validator::make($request->all(),$rules);

        // if($error->fails())
        // {
        //     return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        // }

        if($file)
        {
            $filename = $file->getClientOriginalName();
        

            $formdata = [
                'banner_image' => $filename,
               
            ];

        // $schedule->save();

        Carousel::whereId($id)->update($formdata);
            $file->move(public_path('image'),$filename);
            return response()->json(['status'=>'200','message'=>'the Carousel is updated Successfully']);
        
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
        $data = Carousel::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the Carousel is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }


        //
    }

    public function getCarousel()
    {
        return response()->json(Carousel::all());
    }



}
