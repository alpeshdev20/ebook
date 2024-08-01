<?php

namespace App\Http\Controllers\user;

use Raju\Streamer\Helpers\VideoStream;
use App\helper\ResumeDownload;
use App\Http\Controllers\Controller;
use App\model\Bookread;
use App\model\Book;
use App\model\Bookgenre;
use App\model\Hasread;
use App\Models\app_department;
use App\Models\app_subject;
use App\model\Genre;
use File;
use Illuminate\Http\Request;
use Response;
use Storage;
use Validator;
use DB;

use App\Ulogin;

class BooksreadController extends Controller
{
    public function index()
    {
        return Bookread::all();
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
            'book_id' => 'required',
            'page_no' => 'required',
            'duration' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',

        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => '500', 'error' => $error->errors()->all()]);
        }

        $formdata = [
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'page_no' => $request->page_no,
            'duration' => $request->duration,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,

        ];
        // $schedule->save();

        Bookread::create($formdata);

        return response()->json(['status' => '200', 'message' => 'the  Bookread is created Successfully'], 200);

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
        return response()->json(Bookread::find($id));
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
            'page_no' => 'required',
            'duration' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',

        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => '500', 'error' => $error->errors()->all()]);
        }

        $formdata = [
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'page_no' => $request->page_no,
            'duration' => $request->duration,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];
        Bookread::whereId($id)->update($formdata);

        return response()->json(['status' => '200', 'message' => 'the  Bookread is Updated Successfully']);
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
        $data = Bookread::find($id);
        if ($data->delete()) {
            return response()->json(['status' => '200', 'message' => 'the Bookread is deleted successfully']);
        } else {
            return response()->json(['status' => '500', 'error' => 'Something Went Wrong']);
        }
    }

    public function stream(Request $request, $filename)
    {
         $videosDir = config('larastreamer.basepath');
        // return $videosDir."/".$filename;
        if (file_exists($filePath = $videosDir."/".$filename)) {
            $stream = new VideoStream($filePath);
            return response()->stream(function() use ($stream) {
                $stream->start();
            });
        }
        return response("File doesn't exists", 404);
        //

       /*
       $extentions = $_SERVER['REQUEST_URI'];
        $data = explode(".",$extentions);
       
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if($data[1] == "pdf" || $data[1] == "mp3" || $data[1] == "m4a" || $data[1] == "mkv" || $data[1] == "mp4" || $data[1] == "epub")
            {
                $videosDir = config('larastreamer.basepath');
                if (file_exists($filePath = $videosDir."/".$filename)) {
                    $stream = new VideoStream($filePath);
    
                    return response()->stream(function() use ($stream) {
                        $stream->start();
                    });
                }
            }
        }
        else{
            return response("File doesn't exists", 404);
        }
        */

        /*if(!isset($_SERVER['HTTP_REFERER']) && ($_SERVER['REQUEST_URI']).includes('.pdf')){
            return "File Not Exit"
            exit;
        }
        
        $env_val = env('ALLOWED_DOMAINS');
        $requestHost = parse_url($request->headers->get('origin'),  PHP_URL_HOST);
        if( $env_val == $requestHost)
        {
        }else{
        }*/
        
    }

    public function stream2($filename)
    {
        set_time_limit(0);
        $file = public_path('uploads') . "/" . $filename;
        $download = new ResumeDownload($file);
        $download->process();
        return response($download->process(), 200);
    }
    
    public function getBookPdf($name)
    {
        $filePath = public_path('uploads') . "/" . $name;
        $filePathNew = public_path('uploads') . "/new_" . $name;

        if (!File::exists($filePath)) {
            return response()->json(['status' => '500', 'error' => 'Something did Went Wrong']);
        }
//        if (!File::exists($filePathNew)) {
//		$command = "ghostscript -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=znew_" . $filePathNew . " " .  $filePath;
//	        $output = shell_exec($command);
//        }
//	$output = shell_exec( "gswin64c -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=new-file.pdf test.pdf");
        $pdfContent = File::get($filePath);

        $type = File::mimeType($filePath);
        $fileName = File::name($filePath);

        $size = filesize($filePath);
        $etag = md5($filePath);
        $lastm = date("F d Y H:i:s.", filemtime($filePath));
        return Response::make($pdfContent, 200, [
            'Content-Length' => $size,
            'ETag' => $etag,
            'Last-Modified' => $lastm,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'max-age=2592000, public',
            'Content-Type' => $type,
        ]);
    }

    public function getReadHistory($id)
    {
		$sql = "SELECT * from (select DISTINCT book_id, id, page_number, user_id, created_at, updated_at FROM hasreads ORDER BY id DESC) as books where user_id=" .$id. " GROUP BY book_id order by created_at desc";
		$data = DB::select(DB::raw($sql));
		$data = json_decode(json_encode($data), true);
		//return response()->json($data);
		$resp = array();
		$i = 0;
		foreach($data as $value) {
			$book =  Book::where('id', $value['book_id'])->first();
			if(empty($book))continue;//edit rahul
			$array = array();
			$array['id'] =  $book['id'];
			$array['book_name'] =  $book['book_name'];
            $array['book_image'] =  $book['book_image'];
            $array['publisher'] =  $book['publisher'];
            $array['book_pdf'] =  $book['book_pdf'];
            $array['length'] =  $book['length'];
            $array['author'] =  $book['author'];
            $array['material_type'] =  $book['material_type'];
			
			/* not required for our purpose
			$array['year'] =  $book['year'];
			$array['price'] =  $book['price'];
			$array['summary'] =  $book['summary'];
			$array['tags'] =  $book['tags'];
			*/
			
			
			$resp[$i] = $value;
			$resp[$i]["book"] = $array;			
			$i++;
		}
		return response()->json(['status' => '200', 'message' => 'Retrived', 'data'=>$resp]);
    }
	
	
	
	public function getReadingLists($id)
    {
		
		/*
		the user data should be taken from Auth(). however, that would require a lot of changes in he back/front and
		as the prev. developer designed it in such a way.
		*/
		$user = Ulogin::where('id', $id)->first();
		if(!$user)return response('User not found', 404);
		
		
		try{
		
		// using db raw are the models are not yet ready. need to change later
		// self created reading list first.
		$resp = array();
		$sql = "SELECT * from subscriber_reading_lists where subscriber_id=" .$id. " order by created_at desc";
		$SRLs = DB::select(DB::raw($sql));
		$resp['subscriber_reading_list']=array();
		
		foreach($SRLs as $SRL){
			$list=array();
			$list['id'] = $SRL->id;
			$list['name'] = $SRL->name;
			$list['books'] = array();
			$bq = "SELECT * from reading_list_items where subscriber_reading_list_id=" .$list['id']. " order by created_at desc";
			$bookIds = DB::select(DB::raw($bq));
			foreach($bookIds as $bookId){
				$bookQ = "SELECT * from books where id=" .$bookId->app_material_id. " order by created_at desc";
				$list['books'] = DB::select(DB::raw($bookQ));
			}
			$resp['subscriber_reading_list'][]=$list;
		}
		
		// prescribed list.
		if($user->school_id!=null && $user->level!=null){
			$sql = "SELECT * from prescribed_reading_lists where prescriber_id=" .$user->school_id. " AND level=".$user->level;
			$PRLs = DB::select(DB::raw($sql));
			$resp['prescribed_reading_list'] = array();
			foreach($PRLs as $PRL){
				$list=array();
				$list['id'] = $PRL->id;
				$list['name'] = $PRL->name;
				$list['books'] = array();
				$bq = "SELECT * from reading_list_items where prescribed_reading_list_id=" .$list['id']. " order by created_at desc";
				$bookIds = DB::select(DB::raw($bq));
				foreach($bookIds as $bookId){
					//$bookQ = "SELECT * from books where id=" .$bookId->app_material_id. " order by created_at desc";
					$list['books'][] = Book::where('id', $bookId->app_material_id)->first();
				}
				$resp['prescribed_reading_list'][]=$list;
			}
		}
		//dd(json_encode());
		return response()->json(['status' => '200', 'message' => 'Retrived', 'data'=>$resp]);
		
		}catch(\Exception $ex){
			return response('API Error!', 500);
		}
    }
	
	

    public function recommended($id) {
		$sql = "SELECT * from (select DISTINCT book_id, id, page_number, user_id, created_at, updated_at FROM hasreads ORDER BY id DESC) as books where user_id=" .$id. " GROUP BY book_id order by created_at desc";
		$data = DB::select(DB::raw($sql));
		$data = json_decode(json_encode($data), true);
        $resp = array();
        $count = count($data);
        $max = 5;
        if($count < 5) {
            $max = $count;
        }
        $i = 0;
        $tags = array();
        if($max > 2) {
            for($i = 0; $i < $max; $i++) {
                $value = $data[$i];
                $book =  Book::where('id', $value['book_id'])->first();
                $array = array();
                $array['id'] =  $book['id'];
                $array['book_name'] =  $book['book_name'];
                $array['book_image'] =  $book['book_image'];
                $array['publisher'] =  $book['publisher'];
                $array['year'] =  $book['year'];
                $array['year'] =  $book['year'];
                $array['book_pdf'] =  $book['book_pdf'];
                $array['price'] =  $book['price'];
                $array['length'] =  $book['length'];
                $array['summary'] =  $book['summary'];
                $array['tags'] =  $book['tags'];
                $array['author'] =  $book['author'];
                $array['genre_id'] =  $book['genre_id'];
                $array['subject_id'] =  $book['subject_id'];
                $array['department_id'] =  $book['department_id'];
                $genre_name = Genre::where('id', $book['genre_id'])->first();
                $subject_name = app_subject::where('id', $book['subject_id'])->first();
                $department_name = app_department::where('id', $book['department_id'])->first();
                if($genre_name && $subject_name && $department_name) {
                    $array['genre_name'] =  Genre::where('id', $book['genre_id'])->first()->genre_name;
                    $array['subject_name'] =  app_subject::where('id', $book['subject_id'])->first()->subject_name;
                    $array['department_name'] =  app_department::where('id', $book['department_id'])->first()->department_name;
                } else {
                    $array['genre_name'] =  Genre::first()->genre_name;
                    $array['subject_name'] =  app_subject::first()->subject_name;
                    $array['department_name'] =  app_department::first()->department_name;
                }
                $array['created_at'] =  $book['created_at'];
                $array['updated_at'] =  $book['updated_at'];
                $resp[$i] = $value;
                $resp[$i]["book"] = $array;
            }
            $tags[0]["title"] = "Latest arrivals in " . $resp[0]["book"]["subject_name"];
            $tags[0]["books"] = Book::where('subject_id', $resp[0]["book"]["subject_id"])->latest()->paginate(20);
            $tags[1]["title"] = "Because you read " . $resp[1]["book"]["book_name"];
            $tags[1]["books"] = Book::where('genre_id', $resp[1]["book"]["genre_id"])->latest()->paginate(20);
            $tags[2]["title"] = "Some more titles from " . $resp[0]["book"]["author"];
            $tags[2]["books"] = Book::where('author', $resp[0]["book"]["author"])->latest()->paginate(20);
            $tags[3]["title"] = "Try out latest books on " . $resp[0]["book"]["department_name"];
            $tags[3]["books"] = Book::where('department_id', $resp[0]["book"]["department_id"])->latest()->paginate(20);
            $tags[4]["title"] = "Fresh books that you might like";
            $tags[4]["books"] = Book::latest()->paginate(20);
        } else {
            $data = Book::whereNotNull('genre_id')->whereNotNull('subject_id')->whereNotNull('department_id')->get();
            if(count($data) < 5) {
                $data = Book::whereNotNull('genre_id')->whereNotNull('department_id')->get();
                if(count($data) < 5) {
                    $data = Book::whereNotNull('genre_id')->get();                    
                }
            }
            $max = 5;
            for($i = 0; $i < $max; $i++) {
                $value = $data[$i];
                $book =  Book::where('id', $value['id'])->first();
                $array = array();
                $array['id'] =  $book['id'];
                $array['book_name'] =  $book['book_name'];
                $array['book_image'] =  $book['book_image'];
                $array['publisher'] =  $book['publisher'];
                $array['year'] =  $book['year'];
                $array['year'] =  $book['year'];
                $array['book_pdf'] =  $book['book_pdf'];
                $array['price'] =  $book['price'];
                $array['length'] =  $book['length'];
                $array['summary'] =  $book['summary'];
                $array['tags'] =  $book['tags'];
                $array['author'] =  $book['author'];
                $array['genre_id'] =  $book['genre_id'];
                $array['subject_id'] =  $book['subject_id'];
                $array['department_id'] =  $book['department_id'];
                $genre_name = Genre::where('id', $book['genre_id'])->first();
                $subject_name = app_subject::where('id', $book['subject_id'])->first();
                $department_name = app_department::where('id', $book['department_id'])->first();
                if($genre_name && $subject_name && $department_name) {
                    $array['genre_name'] =  Genre::where('id', $book['genre_id'])->first()->genre_name;
                    $array['subject_name'] =  app_subject::where('id', $book['subject_id'])->first()->subject_name;
                    $array['department_name'] =  app_department::where('id', $book['department_id'])->first()->department_name;
                } else {
                    $array['genre_name'] =  Genre::first()->genre_name;
                    $array['subject_name'] =  app_subject::first()->subject_name;
                    $array['department_name'] =  app_department::first()->department_name;
                }
                $array['created_at'] =  $book['created_at'];
                $array['updated_at'] =  $book['updated_at'];
                $resp[$i]["book"] = $array;
            }
            $tags[0]["title"] = "Latest arrivals in " . $resp[0]["book"]["subject_name"];
            $tags[0]["books"] = Book::where('subject_id', $resp[0]["book"]["subject_id"])->latest()->paginate(20);
            $tags[1]["title"] = "Books similar to " . $resp[1]["book"]["book_name"];
            $tags[1]["books"] = Book::where('subject_id', $resp[1]["book"]["subject_id"])->inRandomOrder()->paginate(20);
            $tags[2]["title"] = "Some more titles from " . $resp[0]["book"]["author"];
            $tags[2]["books"] = Book::where('author', $resp[0]["book"]["author"])->inRandomOrder()->paginate(20);
            $tags[3]["title"] = "Try out latest books on " . $resp[0]["book"]["department_name"];
            $tags[3]["books"] = Book::where('department_id', $resp[0]["book"]["department_id"])->latest()->paginate(20);
            $tags[4]["title"] = "Fresh books that you might like";
            $tags[4]["books"] = Book::latest()->paginate(20);
        }
        return response()->json(['status' => '200', 'message' => 'Retrived', 'data'=>$tags]);
    }



	// edit here
	
    public function genreBooks($id) {
    	$genre = Genre::where('id', $id)->first();
        
        $dept = app_department::where('genre_id', $id)->get();
        $data = array();
        $i = 0;
        foreach($dept as $department) {

            $book_ids = Bookgenre::where('department_id',$department['id'])->groupBy('book_id')->pluck('book_id');

            $data[$i] = array();
            $data[$i]["title"] = $department["department_name"];
            $data[$i]["id"] = $department["id"];
            $data[$i]["books"] = Book::where('department_id', $department["id"])->orWhereIn('id',$book_ids)->inRandomOrder()->paginate(100);
            $i++;
        }
        return response()->json(['status' => '200', 'message' => 'Retrived', 'data'=>$data, 'genre'=>$genre]);
    }

    public function departmentBooks($id) {        
    	$genre = app_department::where('id', $id)->first();
        $genre_name = Genre::where('id',$genre->genre_id)->first();
        $genre['genre_name'] = $genre_name["genre_name"];

        $dept = app_subject::where('department_id', $id)->get();
        $data = array();
        $i = 0;
        foreach($dept as $department) {
            $book_ids = Bookgenre::where('subject_id',$department['id'])->groupBy('book_id')->pluck('book_id');

            $data[$i] = array();
            $data[$i]["title"] = $department["subject_name"];
            $data[$i]["books"] = Book::where('subject_id', $department["id"])->orWhereIn('id',$book_ids)->inRandomOrder()->paginate(100);
            $i++;
        }
        return response()->json(['status' => '200', 'message' => 'Retrived Testing', 'data'=>$data, 'genre'=>$genre]);
    }
}
