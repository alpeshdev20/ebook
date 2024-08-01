<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Hasread;
use Validator;

class HasreadController extends Controller
{
    public function index(){
        return Hasread::all();

    }

    public function store(Request $request)
    {
       // return $request;
        $rules = [
      'user_id' => 'required',
        'book_id' => 'required',
        'page_number' => 'required',
           
         ]; 


        
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

    
        
       
        $formdata = [
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'page_number' => $request->page_number,
           
        ];

       // $schedule->save();

       Hasread::create($formdata);
        return response()->json(['status'=>'200','message'=>'book status saved Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Hasread::find($id));
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
        'page_number' => 'required',
          
        ]; 
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

        $formdata = [
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'page_number' => $request->page_number,
           
           
        ];



     

           
        // $schedule->save();

        Hasread::whereId($id)->update($formdata);
            return response()->json(['status'=>'200','message'=>'the Has read is updated Successfully']);
        
        }
        
        
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Hasread::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the Has read is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }


        //
    }
        
       // return $request;

      

    public function getHasreadStat(Request $request)
    {
	$data = Hasread::where('user_id', $request->user_id)->where('book_id', $request->book_id)->get();
	if(count($data) > 0) {
		return response()->json(['status'=>true,'data'=>$data[count($data) - 1]]);
	}
	return response()->json(['status'=>false,'error'=>'Start Reading Please']);
//        return response()->json($data);
    }
}
