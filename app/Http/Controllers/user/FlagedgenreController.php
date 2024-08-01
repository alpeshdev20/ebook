<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\model\Flagedgenre;
use Validator;

class FlagedgenreController extends Controller
{
    public function index(){

       


    }

    public function store(Request $request)
    {
       // return $request;
        $rules = [
             'genre_id' => 'required',
        'genre_name' => 'required',
           
         ]; 


        
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

    
        
       
        $formdata = [
            'genre_id' => $request->genre_id,
            'genre_name' => $request->genre_name,
           
        ];

       // $schedule->save();

       Flagedgenre::create($formdata);
        return response()->json(['status'=>'200','message'=>'the Flaged genre is created Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Flagedgenre::find($id));
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
            'genre_id' => 'required',
       'genre_name' => 'required',
          
        ]; 


     

            $rules = [
                'genre_id' => 'required',
           'genre_name' => 'required',
              
            ]; 

        // $schedule->save();

        Flagedgenre::whereId($id)->update($formdata);
            return response()->json(['status'=>'200','message'=>'the Flaged genre is updated Successfully']);
        
        }
        
        
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Flagedgenre::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the Flagedgenre is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }


        //
    }

    public function getFlagedgenre()
    {
        return response()->json(Flagedgenre::all());
    }

}
