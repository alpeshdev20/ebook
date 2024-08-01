<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\model\Userfav;
use Validator;
use Illuminate\Support\Facades\DB;


class UserfavController extends Controller
{
    public function index(){
        return Userfav::all();

    }

    public function store(Request $request)
    {
       // return $request;
        $rules = [
             'user_id' => 'required',
        'book_id' => 'required',
           
         ]; 


        
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

    
        
       
        $formdata = [
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
           
        ];

       // $schedule->save();

       Userfav::create($formdata);
        return response()->json(['status'=>'200','message'=>'the User favorite book is created Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	$data = Userfav::where('user_id',$id)
            ->leftJoin('books', 'books.id', '=', 'userfavs.book_id')
	    ->get();
        return response()->json($data);
    }

    public function verifyFav(Request $request)
    {
        $data = Userfav::where('user_id',$request->user_id)->where('book_id', $request->book_id)->get();
	$stats = array();
        $stats['status'] = false;
        $stats['data'] = 'NotLiked';
	if(count($data) > 0) {
		$stats = array();
		$stats['status'] = true;
		$stats['data'] = 'HasLiked';
		return response()->json($stats);
	} else {
                $stats = array();
                $stats['status'] = false;
                $stats['data'] = 'NotLiked';
		return response()->json($stats);
	}
        return response()->json($stats);
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
          
        ]; 
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

        $formdata = [
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
           
        ];



     

           
        // $schedule->save();

        Userfav::whereId($id)->update($formdata);
            return response()->json(['status'=>'200','message'=>'the user favorite book is updated Successfully']);
        
        }
        
        
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function RemoveFav(Request $request)
    {
      // return $request;

        $rules = [
            'user_id' => 'required',
            'book_id' => 'required'
        ]; 
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

//        $user_id=$request->user_id;
//        $book_id=$request->book_id;


/*        $getId= DB::select( DB::raw("SELECT id FROM userfavs WHERE user_id = $user_id AND book_id= $book_id") );
        //return $data['0']->id;
        $exist= DB::table('userfavs')->where('id',$getId['0']->id)->exists();
        if($exist == false)

        {
            return ['message'=>'this user id  and book id does not exist'];      
        }
*/
        $data = Userfav::where('user_id',$request->user_id)->where('book_id', $request->book_id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the user fav book is deleted successfully']);
        }else
        {
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }

        
       // return $request;

      


        //
    }

    public function getUserfav()
    {
        return response()->json(Userfav::all());
    }

}
