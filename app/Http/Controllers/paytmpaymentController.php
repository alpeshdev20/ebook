<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Indipay;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription_plan;
use App\Models\Subscription;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;
use App\model\ULogin;
use Illuminate\Support\Facades\Redirect;
require_once("PaytmChecksum.php");
require_once('vendor/autoload.php');
use paytm\checksum\PaytmChecksumLibrary;

use Validator;



class paytmpaymentController extends Controller
{
    public function pay(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'subscription_id' => 'required',
        ];
        
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => $error->errors()->all()], 200);
        }

        // $email = $request->user_id;
        // $password = $request->subscription_id;
        $plan=Subscription_plan::find($request->subscription_id);
        $user=ULogin::find($request->user_id);
        $transaction_id=(string) Str::uuid();
        $subs=new Subscription();
        $subs->user_id=$request->user_id;
        $subs->transaction_id=$transaction_id;
        $subs->subscription_plan_id=$request->subscription_id;
        $subs->plan_validity=$plan->validity;
        $subs->save();
        $parameters = [
            'tid' => mt_rand(),
            'order_id' => $transaction_id,
            'amount' => $plan->price,
            'billing_name' => $user->name,
            // 'billing_address' => '',
            // 'billing_city' => $user_city,
            // 'billing_state' => '',
            // 'billing_zip' => '',
            'billing_country' => 'India',
            'billing_tel' => $user->mobile,
            'billing_email' => $user->email
          ];
    
          $order = Indipay::gateway('CCAvenue')->prepare($parameters);
          $uuidFileName =  (string) Str::uuid().'.html';
          $uuidFileName = 'pay/'.$uuidFileName;
          $form = Indipay::process($order);
          Storage::disk('public')->put($uuidFileName, $form);
          $urlPath = url('')."/".$uuidFileName;
        //   return $urlPath;
          return response()->json(['status' => '200', 'message' => 'Payment url','order_id' => $transaction_id, 'data' => $urlPath]);
    }
}
