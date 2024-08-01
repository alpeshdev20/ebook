<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Bookgenre;
use App\model\Book;
use App\model\Genre;
use App\Models\app_department;
use DB;

use Validator;

class BookgenreController extends Controller
{
    public function index()
    {
        return Bookgenre::all();
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
        $rules = ['book_id' => 'required', 'genre_id' => 'required', ];
        $error = Validator::make($request->all() , $rules);
        if ($error->fails())
        {
            return response()
                ->json(['status' => '500', 'error' => $error->errors()
                ->all() ]);
        }
        $formdata = ['book_id' => $request->book_id, 'genre_id' => $request->genre_id, ];
        // $schedule->save();
        Bookgenre::create($formdata);
        return response()->json(['status' => '200', 'message' => 'the genre is created Successfully'], 200);
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
    public function bookgenreIDS($id)
    {
        //
        return Bookgenre::where('book_id', $id)->get();
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
        return response()->json(Bookgenre::find($id));
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
        $rules = ['book_id' => 'required', 'genre_id' => 'required', ];
        $error = Validator::make($request->all() , $rules);
        if ($error->fails())
        {
            return response()
                ->json(['status' => '500', 'error' => $error->errors()
                ->all() ]);
        }
        $formdata = ['book_id' => $request->book_id, 'genre_id' => $request->genre_id, ];
        Bookgenre::whereId($id)->update($formdata);
        return response()->json(['status' => '200', 'message' => 'the genre is Updated Successfully']);
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
        $data = Bookgenre::find($id);
        if ($data->delete())
        {
            return response()
                ->json(['status' => '200', 'message' => 'the Genre is deleted successfully']);
        }
        else
        {
            return response()
                ->json(['status' => '500', 'error' => 'Something Went Wrong']);
        }
    }
    public function GetbookbyGenreold($id)
    {
        //return $id;
        $book_id = BookGenre::where('genre_id', $id)->get();
        //	return $book_id;
        $array = array();
        $i = 0;
        foreach ($book_id as $id)
        {
            $data = Book::find($id->book_id);
            if ($data)
            {
                $array[$i] = $data;
                $i++;
            }
            //$i++;
            
        }
        return $array;
    }

    public function GetbookbyGenre($id)
    {
        $ac_id = $id;
        $results = Genre::where('id', $id)->get();
        $array = array();
        $i = 0;
        foreach ($results as $genre)
        {
            $book_id = BookGenre::where('genre_id', $genre->id)
                ->get();
            $j = 0;
            $kx = 0;
            foreach ($book_id as $id)
            {
                $data = Book::find($id->book_id);
                if ($data)
                {
                    $books[$j] = $data;
                    $j++;
                }
            }
            if (count($books) > 0)
            {
                $array[$i]['genre'] = $genre['genre_name'];
                $array[$i]['genre_id'] = $genre['id'];
                $array[$i]['count_books'] = count($books);
                $array[$i]['books'] = $books;
                $i++;
            }
        }
        $datum = array();
        foreach ($array as $newdata)
        {
            //return $newdata;
            if ($newdata['genre_id'] == $ac_id)
            {
                $datum = $newdata;
            }
        }
        return $datum;
    }

    public function CountBookbysinglegenre()
    {
        $results = Genre::all();
        $array = array();
        $i = 0;
        foreach ($results as $genre)
        {
            // $books = Book::where('genre_id', $genre['id'])->get();

            $book_ids = Bookgenre::where('genre_id',$genre['id'])->groupBy('book_id')->pluck('book_id');

            $books = Book::where('genre_id', $genre['id'])->orWhereIn('id',$book_ids)->get();
            

            if (count($books) > 0)
            {
                $array[$i]['genre'] = $genre['genre_name'];
                $array[$i]['genre_id'] = $genre['id'];
                $array[$i]['count_books'] = count($books);
                $array[$i]['books'] = $books;
                if ($genre['id'] == 22)
                {
                    $nx = $array[0];
                    $ny = $array[$i];
                    $array[$i] = $nx;
                    $array[0] = $ny;
                }
                $i++;
            }
        }
        return $array;
    }
}
