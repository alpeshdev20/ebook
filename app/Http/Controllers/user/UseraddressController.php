<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Useraddress;
use Validator;

class UseraddressController extends Controller
{
    public function index()
    {
        return Useraddress::all();
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
            'address'  => 'required',
            'district'  => 'required',
            'city'  => 'required',
            'state'  => 'required',
            'country'  => 'required',
            'latitude'  => 'required',  
            'longitude'  => 'required', 
        ]; 
        
      
     
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
      
       
        $formdata = [
            'user_id' => $request->user_id,
            'address'  => $request->address,
            'district'  => $request->district,
            'city'  => $request->city,
            'state'  => $request->state,
            'country'  =>$request->country,
            'latitude'  => $request->latitude,
            'longitude'  => $request->longitude,
        ];
       // $schedule->save();

       Useraddress::create($formdata);
     
       return response()->json(['status'=>'200','message'=>'the User address is created Successfully'],200);

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
        return response()->json(Useraddress::find($id));
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
            'address'  => 'required',
            'district'  => 'required',
            'city'  => 'required',
            'state'  => 'required',
            'country'  => 'required',
            'latitude'  => 'required',  
            'longitude'  => 'required',  
            
        ]; 
       
     
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
     
       
        $formdata = [
            'user_id' => $request->user_id,
            'address'  => $request->address,
            'district'  => $request->district,
            'city'  => $request->city,
            'state'  => $request->state,
            'country'  =>$request->country,
            'latitude'  => $request->latitude,
            'longitude'  => $request->longitude,
        ];
        Useraddress::whereId($id)->update($formdata);
       
        return response()->json(['status'=>'200','message'=>'the User address is Updated Successfully']);
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
        $data = Useraddress::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the User address is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }
    }
    
}