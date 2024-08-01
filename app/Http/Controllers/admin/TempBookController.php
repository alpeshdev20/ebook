<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\TempBooks;
use Validator;

class TempBookController extends Controller
{
    public function index()
    {
        return TempBooks::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'datum' => 'required'
        ]; 
        
        $file1 = $request->file('datum');
        if(!$file1) {
            return response()->json(['status'=>'failed','error'=>'File datum upload Failed']);
        }

        $data = TempBooks::all();

        $new_id = 1;

        if(count($data) > 0) {
            $new_id = count($data) + 1;
        } 

        $file_name1 = $new_id . "_" . $file1->getClientOriginalName();

        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'failed','error'=>$error->errors()->all()]);
        }

       
        $formdata = [
            'datum'  => $file_name1
        ];
       // $schedule->save();

       TempBooks::create($formdata);
       $file1->move(public_path('uploads'),$file_name1);

       $data = TempBooks::latest()->first();

       return response()->json(['status'=>'success','message'=>'tempfile created', 'data'=>$data, 'error'=>''],200);

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
        return response()->json(TempBooks::find($id));
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
        // $data = Book::find($id);
        // if($data->delete())
        // {
        //     return response()->json(['status'=>'200','message'=>'the E-book is deleted successfully']);
        // }else{
        //     return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        // }
    }
}
