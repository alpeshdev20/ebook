<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Genre;
use App\model\Genresort;
use Validator;
use DB;

class GenreController extends Controller
{
    public function index()
    {
        return Genre::all();
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
         
            'genre_name'  => 'required|unique:genres',
        
        ]; 

        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

       
        $formdata = [
          
            'genre_name'  => $request->genre_name,
            

        ];
       // $schedule->save();

       Genre::create($formdata);

       $genre = Genre::where('genre_name',$request->genre_name)->first();
          $i=0;
       $results = DB::select( DB::raw("SELECT MAX(sort) as count FROM genresorts") );
//       return $results;
       if($results[0]->count == null) {
           $results[0]->count = 0;
       }
       $i++;
      // return  $results;
       

       
       // explode the genre 
      // $result = $request->count;
      // for($i = 0 ; $i<$result ; $i++){
    //    $dataGenre = new Genresort();
    //      $dataGenre->genre_id = $genre->id;

    //     $dataGenre->sort = $result[$i];
    //     $dataGenre->save();
        $formdata = [
          
            'genre_id'  => $genre->id,
            'sort' =>$results[0]->count

        ];
       // $schedule->save();

       Genresort::create($formdata);
   // } 
       
       //sava the book and genre id in book genre table 
    //    for($i = 0 ; $i< count($genre) ; $i++){
          
    //       $dataGenre = new Genresort();
    //      // $dataGenre->book_id = $book->id;
    //       $dataGenre->genre_id = $genre[$i];
    //       $dataGenre->save();   
    //    }


       return response()->json(['status'=>'200','message'=>'the genre is created Successfully'],200);

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
	return response()->json(Genre::find($id));
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
        return response()->json(Genre::find($id));
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
        
            'genre_name'  => 'required|unique:genres', 
        ]; 
        
     
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
       
       
        $formdata = [
          
            'genre_name'  => $request->genre_name,
          
        ];
        Genre::whereId($id)->update($formdata);
    
        return response()->json(['status'=>'200','message'=>'the genre is Updated Successfully']);
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
        $data = Genre::find($id);
      
        if($data->delete())
        {
            $result= Genre::all();
            return response()->json(['status'=>'200','message'=>'the Genre is deleted successfully','data'=>$result]);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }
    }
 


}
