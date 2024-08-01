<?php

namespace App\Http\Controllers;

use App\model\User;
use App\Models\Subscription_plan;
use App\Models\Subscriber;
use App\ExternalApp; 
use App\model\ULogin; 
use JWTAuth; 
use Auth;
use Hash; 

use Illuminate\Http\Request;

class DemoController extends Controller
{ 
    public function get_user_data(Request $request)
    {
      $user = JWTAuth::parseToken()->authenticate();
      $daa = ULogin::where('id', $user->id)->get();
      $externalContent = file_get_contents('http://checkip.dyndns.com/');
      preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
      $externalIp = $m[1];
      if($externalIp == '65.1.3.203') {
        if(count($daa) > 0) {
          return response()->json(['user' => $daa], 200);
        }  
      }
      if(count($daa) > 0) {
        return response()->json([$daa], 200);
      }
      return response()->json(['user_not_found'], 404);
    }




    public function add_external_link(Request $request)
    
    {
         
  return   $subs = Subscription_plan::get(); 
    $pub_key = $request->header('public-key');
    if(empty($pub_key))
    {
      return redirect('/'); 
    }
    else
    {
      $e = ExternalApp::where('public_key',$pub_key)->get();

     
      $private_key = $e->private_key; 
    $new_pub = Hash::make($pub_key); 
    if(Password_verify($pub_key,$new_pub))
    {
      $user_email = $request->input('email');
         $plan_id = $request->input('plan_id'); 
          $status = $request->input('status'); 

    }
 
      
    }
       
 




  

    
// 110015





      
    }
   
}
