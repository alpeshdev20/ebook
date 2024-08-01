<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Resetpassword;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\model\ULogin;
use JWTAuth;
use App\Models\Subscriber;


class ResetpasswordController extends Controller
{
    public function index()
    {
        return Resetpassword::all();

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
            'user_id'  => 'required',
            'email'  => 'required',
            'used'  => 'required',

        ]; 


        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            $data = $error->errors()->all();
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
       
            //$otp = mt_rand(100000, 999999);
              $formdata = [
                'user_id' => $request->user_id,
                'email' => $request->email,
                'used' => $request->used,
                //'password'  =>Hash::make($request->password),
               // 'OTP' =>  Str::random(6),
              // 'OTP' => $otp,
               'token'  => Str::random(128),    
            ];
        
            Resetpassword::create($formdata);
            ob_start();
            $text = "YOUR OTP IS : " . $formdata['token'];
            $url = "https://control.msg91.com/api/sendhttp.php?authkey=217623AHdY3axe5b0bcd2b&mobiles=" . $request->mobile . "&message=" . $text . "&sender=NETBKF&route=4&country=91";
     
     
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $server_output = curl_exec($ch);
            curl_close ($ch);
            ob_end_clean();
            if($server_output)
            {
                return response()->json(['message'=>'token Send'],200); 
            }
            return response()->json(['error'=>'something went wrong please try again!'],200); 

             
       
    }


    public function ResetPassword(Request $request, $id){
        // $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'email' => 'required|',
            'password' => 'required',
            'token' =>'required',
        ];
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

        $email = $request->input('email');
        $token = $request->input('token');
   
        //	$user = Adminlogin::all();
        //	return $user;
        // $user = ULogin::where('email', '=', $email)
        // ->orWhere('token', '=', $token)
        // ->get();

        $user = ULogin::where('email', '=', $email)->get();
        $user2 = ResetPassword::where('token', '=', $token)->get();
        $data= ULogin::all();


        $formdata = [
            // 'name' => $request->name,
            // 'email' => $request->email,
            // 'mobile' => $request->mobile,
            'password'  =>Hash::make($request->password),
           // 'OTP' =>  Str::random(6),
        //    'OTP' => mt_rand(100000, 999999),

        //     'token'  => Str::random(128),    
        ];
        ULogin::whereId($id)->update($formdata);
       
        return response()->json(['status'=>'200','message'=>'the password is Updated Successfully']);





    }

    public function ResetUserPassword(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $rules = [            
            'password' => 'required',            
        ];
        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }

        // $email = $request->input('email');
        // $token = $request->input('token');
   
        //	$user = Adminlogin::all();
        //	return $user;
        // $user = ULogin::where('email', '=', $email)
        // ->orWhere('token', '=', $token)
        // ->get();

        // $user = ULogin::where('email', '=', $email)->get();
        // $user2 = ResetPassword::where('token', '=', $token)->get();
        

        $formdata = [
            // 'name' => $request->name,
            // 'email' => $request->email,
            // 'mobile' => $request->mobile,
            'password'  =>Hash::make($request->password),
           // 'OTP' =>  Str::random(6),
        //    'OTP' => mt_rand(100000, 999999),

        //     'token'  => Str::random(128),    
        ];
        ULogin::whereId($user->id)->update($formdata);
        $credentials=[
            'email' => $user->email,
            'password'  =>$request->password,
        ];
        $token = auth('api')->attempt($credentials);
        $subInfo = Subscriber::where('user_id', $user->id)->get();
        return response()->json(['status'=>'200','subInfo'=>$subInfo,'data' => $user,'message'=>'the password is Updated Successfully','token'=>$token]);





    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(User::find($id)); 
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
            'name'  => 'required',
            'email'  => 'required|email',
            'mobile'  => 'required',
            'password'  => 'required',
            'mobile'  => 'required', 
            
        ]; 
       

        $error = Validator::make($request->all(),$rules);

        if($error->fails())
        {
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
     
       
        $formdata = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password'  =>Hash::make($request->password),
           // 'OTP' =>  Str::random(6),
           'OTP' => mt_rand(100000, 999999),

            'token'  => Str::random(128),    
        ];
        User::whereId($id)->update($formdata);
       
        return response()->json(['status'=>'200','message'=>'the User is Updated Successfully']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::find($id);
        if($data->delete())
        {
            return response()->json(['status'=>'200','message'=>'the User is deleted successfully']);
        }else{
            return response()->json(['status'=>'500','error'=>'Something Went Wrong']);
        }
    }

    public function verifyLogin(Request $request)
    {
        $rules = [
            'mobile' => 'required',
            'otp' => 'required'
        ];

        $error = Validator::make($request->all(),$rules);
        
        if($error->fails())
        {
            $data = $error->errors()->all();
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
        $data=User::where('mobile','=',$request->mobile)->first();
        if($data->OTP==$request->otp){
            return response()->json(['message'=>'Otp matched','data'=>$data],200); 
        }else{
            return response()->json(['message'=>'Otp does not matched'],200); 


        }
       // return $data;
    }

    public function userLogin(Request $request)
    {
         $ip = $request->ips();
         return $ip;
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $error = Validator::make($request->all(),$rules);
        
        if($error->fails())
        {
            $data = $error->errors()->all();
            return response()->json(['status'=>'500','error'=>$error->errors()->all()]);
        }
        $data= User::where('email','=',$request->email)->first();
        if(Hash::check($request->password,$data->password)){
           
            $formdata = [
                'user_id' => $data->id,
                'ip_address'  => $request->Ip(),
                
            ];
           // $schedule->save();
    
           Userlog::create($formdata);
           return response()->json(['message'=>'login successfully matched','data'=>$data],200); 
        
        }else{
            return response()->json(['message'=>'email or password does not matched'],200); 
        }
       // return $data;
    }



}
