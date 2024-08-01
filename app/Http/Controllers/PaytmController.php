<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PaytmWallet;
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

class PaytmController extends Controller
{
  
    public function order()
      {
       $rules = [
            'user_id' => 'required',
            'subscription_id' => 'required',
        ];
        
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => $error->errors()->all()], 200);
        }


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
            "requestType"   => "Payment",
            "mid" => "nyMhCF65590556327274",
            "websiteName"   => "WEBSTAGING",
            "orderId"       => "ORDERID_98765",
            "callbackUrl"   => "https://merchant.com/callback",
            "txnAmount"     => [
            "value"     => "1.00",
            "currency"  => "INR",
            ],
             "userInfo"      => [
             "custId"    => "CUST_001",
             ],
          ];


        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order->id,
          'user' => $user->id,
          'mobile_number' => $user->phonenumber,
          'email' => $user->email,
          'amount' => $order->amount,
          'callback_url' => 'http://merchant.com/payment/status'
        ]);
        return $payment->receive();
    }


    public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        if($transaction->isSuccessful()){
          //Transaction Successful
        }else if($transaction->isFailed()){
          //Transaction Failed
        }else if($transaction->isOpen()){
          //Transaction Open/Processing
        }
        $transaction->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        $transaction->getOrderId(); // Get order id
        $transaction->getTransactionId(); // Get transaction id
    } 

    public function refund(){
        $refundStatus = PaytmWallet::with('refund_status');
        $refundStatus->prepare([
            'order' => $order->id,
            'reference' => "refund-order-4", // provide reference number (the same which you have entered for initiating refund)
        ]);
        $refundStatus->check();
        
        $response = $refundStatus->response(); // To get raw response as array
        
        if($refundStatus->isSuccessful()){
          //Refund Successful
        }else if($refundStatus->isFailed()){
          //Refund Failed
        }else if($refundStatus->isOpen()){
          //Refund Open/Processing
        }else if($refundStatus->isPending()){
          //Refund Pending
        }
    }
}
