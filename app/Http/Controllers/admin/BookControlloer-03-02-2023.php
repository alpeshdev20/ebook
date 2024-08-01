<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\app_department;
use App\Models\app_subject;
use App\Models\book_publisher;
use App\Models\language;
use App\Models\material;
use App\model\Book;
use App\model\Bookgenre;
use App\model\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class BookController extends Controller
{
    public function index()
    {
        $data = Book::all();
        $array = array();
        for ($i = 0; $i < count($data); $i++) {

            $array[$i]['id'] = $data[$i]['id'];
            $array[$i]['book_name'] = $data[$i]['book_name'];
            $array[$i]['book_image'] = $data[$i]['book_image'];
            $array[$i]['publisher'] = $data[$i]['publisher'];
            $array[$i]['year'] = $data[$i]['year'];
            $array[$i]['book_pdf'] = $data[$i]['book_pdf'];
            $array[$i]['price'] = $data[$i]['price'];
            $array[$i]['summary'] = $data[$i]['summary'];
            $array[$i]['tags'] = $data[$i]['tags'];
            $array[$i]['author'] = $data[$i]['author'];
            $array[$i]['created_at'] = $data[$i]['created_at'];
            $array[$i]['updated_at'] = $data[$i]['updated_at'];

            $bookgenre = BookGenre::where('book_id', $data[$i]['id'])->get();
            $genre = array();
            for ($j = 0; $j < count($bookgenre); $j++) {

                $g = Genre::find($bookgenre[$j]->genre_id);
                if ($g) {
                    $genre_name = Genre::find($bookgenre[$j]->genre_id);
                    $genre[$j] = $genre_name->genre_name;

                }
            }
            // return $genre;
            if (count($genre) > 0) {
                $array[$i]['genre'] = implode(",", $genre);
            } else {
                $array[$i]['genre'] = "";
            }

        }
        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $rules = [
            'book_name' => 'required',
            'book_image' => 'required',
            'publisher' => 'required',
            'year' => 'required',
            'genre' => 'required',
            'book_pdf' => 'required',
            'price' => 'required',
            'summary' => 'required',
            'tags' => 'required',
            'author' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => '500', 'error' => $error->errors()->all()]);
        }

        $formdata = [
            'book_name' => $request->book_name,
            'book_image' => $request->book_image,
            'publisher_id' => $request->publisher,
            'year' => $request->year,
            'book_pdf' => $request->book_pdf,
            'price' => $request->price,
            'summary' => $request->summary,
            'tags' => $request->tags,
            'author' => $request->author,
        ];
        // $schedule->save();

        Book::create($formdata);

        //Fetch the data of book for inserting the genre data in book genre table
        $book = Book::where('book_name', $request->book_name)->first();

        // explode the genre
        $genre = $request->genre;

        //sava the book and genre id in book genre table
        for ($i = 0; $i < count($genre); $i++) {

            $dataGenre = new BookGenre();
            $dataGenre->book_id = $book->id;
            $dataGenre->genre_id = $genre[$i];
            $dataGenre->save();
        }

        return response()->json(['status' => '200', 'message' => 'the E-book is created Successfully'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Book::where('id', $id)->first();
        if (!$data) {
            return response()->json(['status' => '200', 'message' => 'No book found'], 200);
        }
        if (language::where('id', $data['language'])->first()) {
            $data['language'] = language::where('id', $data['language'])->first()->language_name;
        } else {
            $data['language'] = "English";
        }
        if (material::where('id', $data['material_type'])->first()) {
            $data['object_type'] = material::where('id', $data['material_type'])->first()->material_type;
        } else {
            $data['object_type'] = "book";
        }
        $genre_name = Genre::where('id', $data['genre_id'])->first();
        $subject_name = app_subject::where('id', $data['subject_id'])->first();
        $department_name = app_department::where('id', $data['department_id'])->first();
        $publisher = book_publisher::where('id', $data['publisher_id'])->first();
        if ($genre_name && $subject_name && $department_name) {
            $data['genre_name'] = Genre::where('id', $data['genre_id'])->first()->genre_name;
            $data['subject_name'] = app_subject::where('id', $data['subject_id'])->first()->subject_name;
            $data['department_name'] = app_department::where('id', $data['department_id'])->first()->department_name;
        } else {
            $data['genre_name'] = Genre::first()->genre_name;
            $data['subject_name'] = app_subject::first()->subject_name;
            $data['department_name'] = app_department::first()->department_name;
        }
        if ($publisher) {
            $data["publisher"] = $publisher->publisher;
        } else {
            $data["publisher"] = "TEST";
        }
        $data["sugbooks"] = Book::where('subject_id', $data["subject_id"])->where('id', '!=', $data['id'])->inRandomOrder()->paginate(20);
        return $data;
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
        return response()->json(Book::find($id));
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
            'book_name' => 'required',
            'book_image' => 'required|mimes:jpeg,jpg',
            'publisher_id' => 'required',
            'year' => 'required',
            'genre_id' => 'required',
            'book_pdf' => 'required|mimes:pdf',
            'price' => 'required',
            'summary' => 'required',
            'tags' => 'required',
            'author' => 'required',

        ];

        $file1 = $request->file('book_image');
        $file_name1 = $file1->getClientOriginalName();

        $file2 = $request->file('book_pdf');
        $file_name2 = $file2->getClientOriginalName();

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => '500', 'error' => $error->errors()->all()]);
        }
        if (!$file1) {
            return response()->json(['status' => '501', 'error' => 'Book Image is Required']);
        }

        if (!$file2) {
            return response()->json(['status' => '501', 'error' => 'Book pdf is Required']);
        }

        $formdata = [
            'book_name' => $request->book_name,
            'book_image' => $file_name1,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'book_pdf' => $file_name2,
            'price' => $request->price,
            'summary' => $request->summary,
            'tags' => $request->tags,
            'author' => $request->author,
        ];
        Book::whereId($id)->update($formdata);
        $file1->move(public_path('book_image'), $file_name1);
        $file2->move(public_path('book_pdf'), $file_name2);
        return response()->json(['status' => '200', 'message' => 'the E-book is Updated Successfully']);
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
        $data = Book::find($id);
        if ($data->delete()) {
            $result = Book::all();
            return response()->json(['status' => '200', 'message' => 'the E-book is deleted successfully', 'data' => $result]);
        } else {
            return response()->json(['status' => '500', 'error' => 'Something Went Wrong']);
        }
    }

    public function recent()
    {
        // JWTAuth::parseToken()->authenticate();
        $data = Book::orderBy('created_at', 'desc')->get();
        return response()->json($data);
    }

    public function getSearchBooks(Request $request)
    {
        $book = $request->book_name;
        // $query = Book::where('book_name', 'LIKE', '%' . $book . '%')
        //     ->get();
        if(isset($request->section)&&!empty($request->section)){
            $section=$request->section;
            switch($section){
                case 'book':
                $query = Book::query();
                $columns = [
                    'book_name',
                    /*'book_image',
                    'publisher_id',
                    'year',
                    'book_pdf',
                    // 'summary',
                    'tags', 
                    'author',*/
                ];
                foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $book . '%');
                }
                $books = $query->where('material_type',5)->get();
                return response()->json($books);
                case 'audiobook':
                $query = Book::query();
                $columns = [
                    'book_name',
                    /*'book_image',
                    'publisher_id',
                    'year',
                    'book_pdf',
                    // 'summary',
                    'tags', 
                    'author',*/
                ];
                foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $book . '%');
                }
                $books = $query->where('material_type',4)->get();
                return response()->json($books);
                case 'video':
                $query = Book::query();
                $columns = [
                    'book_name',
                    /*'book_image',
                    'publisher_id',
                    'year',
                    'book_pdf',
                    // 'summary',
                    'tags', 
                    'author',*/
                ];
                foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $book . '%');
                }
                $books = $query->where('material_type',2)->get();
                return response()->json($books);
                case 'class_notes':
                $query = Book::query();
                $columns = [
                    'book_name',
                    /*'book_image',
                    'publisher_id',
                    'year',
                    'book_pdf',
                    // 'summary',
                    'tags', 
                    'author',*/
                ];
                foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $book . '%');
                }
                $books = $query->where('material_type',3)->get();
                return response()->json($books);
                default :
                    $query = Book::query();
                    $columns = [
                        'book_name',
                        /*'book_image',
                        'publisher_id',
                        'year',
                        'book_pdf',
                        // 'summary',
                        'tags', 
                        'author',*/
                    ];
                    foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', '%' . $book . '%');
                    }
                    $books = $query->get();
                    return response()->json($books);
    
            }
        }
        $query = Book::query();
        $columns = [
            'book_name',
            /*'book_image',
            'publisher_id',
            'year',
            'book_pdf',
            // 'summary',
            'tags', 
            'author',*/
        ];
        foreach ($columns as $column) {
            $query->orWhere($column, 'LIKE', '%' . $book . '%');
        }
        $books = $query->get();
        return response()->json($books);
    }

    public function getFreeBooks(Request $request)
    {   
        $results = Genre::all();
        $array = array();
        $i = 0;
        foreach ($results as $genre)
        {
            $books = Book::where(['genre_id'=> $genre['id'],'mat_category'=>0])->get();
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

        return response()->json($array);
    }

    public function AllGenre()
    {

        $query = DB::select(DB::raw("SELECT genre FROM books"));
        // $data=explode(",",$query);
        return response()->json($query);

    }

    public function BookByGenry(Request $request)
    {

        $genre = $request->genre;
        //$query = "";
        // return response()->json($query);

        $query = Book::where('genre', '=', $genre)->get();
        return response()->json($query);

    }

    public function Randombook()
    {

        $query = DB::table('books')
            ->inRandomOrder()
            ->get();
        return response()->json($query);

    }

}
