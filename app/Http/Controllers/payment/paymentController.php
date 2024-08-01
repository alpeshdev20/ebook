<?php

namespace App\Http\Controllers\payment;

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
use Validator;



class paymentController extends Controller
{
	
	private static $IS_STAGING=false;
	private static $MID='voIxzV04284252924708';
	private static $MKEY='8sA#iLD3m&MOgLct';

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
        
		// there are no checks in place to handle invalid plan or user id.
        $plan=Subscription_plan::find($request->subscription_id);
        $user=ULogin::find($request->user_id);
        $transaction_id=(string) Str::uuid();
		
        $subs=new Subscription(); // Subscription should be read as transaction. it is keeping records for txns. both sucess and fail.
        $subs->user_id=$request->user_id;
        $subs->transaction_id=$transaction_id;
        $subs->subscription_plan_id=$request->subscription_id;
        $subs->plan_validity=$plan->validity;
        $subs->save(); // save this transaction
		
		
		
		$parameters = [
			/*'TID' => mt_rand(),*/
			'MID'				=> 	self::$MID,
			'ORDER_ID' 			=> $transaction_id,
			'CALLBACK_URL'		=>	"https://ebook.netbookflix.com/".env('PAYTM_REDIRECT_URL', ''),//url(env('PAYTM_REDIRECT_URL', '')),
			/*'FREQUENCY'			=> $plan->validity,*/
			'TXN_AMOUNT' 		=> number_format((float)$plan->price,2),

			'CUST_ID' 			=> $user->id,
			'CUST_NAME' 		=> $user->name,
			'CUST_PHONE' 		=> $user->mobile,
			'CUST_EMAIL' 		=> $user->email,
		];
		
		$merchantKey 	= self::$MKEY;
		
		$txnRes = PaytmHelper::getTransactionId($parameters, $merchantKey, self::$IS_STAGING);
	
		if($txnRes['status']==='ok'){
			$pgHMTL  = '';
			$pgHMTL .= '<html><head><title>NBF Relay</title></head><body><p>Redirecting to Paytm...</p>';
			$pgHMTL .= '      <form method="post" action="'.$txnRes['data']['TXN_URL'].'" name="paytm">';
			$pgHMTL .= '		   <input type="hidden" name="paymentMode" value="UPI">';
			$pgHMTL .= '		   <input type="hidden" name="mid" value="'.$txnRes['data']['MID'].'">';
			$pgHMTL .= '		   <input type="hidden" name="orderId" value="'.$txnRes['data']['ORDER_ID'].'">';
			$pgHMTL .= '		   <input type="hidden" name="txnToken" value="'.$txnRes['data']['TXN_TOKEN'].'"></form>';
			$pgHMTL .= '<script type="text/javascript"> document.paytm.submit(); </script></body></html>';
			
			
			$responseHtmlFIle = ($txnRes['data']['ORDER_ID']).'.html';
			Storage::disk('public')->put('pay/'.$responseHtmlFIle, $pgHMTL);
			$urlPath = url('pay/'.$responseHtmlFIle);
			return response()->json(['status' => '200', 'message' => 'Payment url','order_id' => "$transaction_id", 'data' => "$urlPath"]);
			//return $pgHMTL;
		}else{
			return response()->json(['status' => 'false', 'error' => $txnRes['data']], 200);
		}
        
    }

		private function arrValOrNull($array,$key){
			//not returning '' but null
			return ((array_key_exists($key, $array))?($array[$key]):null);
		}

    public function confirmPayment(Request $request)
    {
	    	
	    	$paytmResp = $request->toArray();

		Subscription::where('transaction_id',$paytmResp['ORDERID'])
		->update([
			'transaction_id'	=>$this->arrValOrNull($paytmResp,'TXNID'),
			'order_status'		=>$this->arrValOrNull($paytmResp,'STATUS'),
			'failure_message'	=>$this->arrValOrNull($paytmResp,'RESPMSG'),
			'status_code'		=>$this->arrValOrNull($paytmResp,'RESPCODE'),
			'status_message'	=>$this->arrValOrNull($paytmResp,'RESPMSG'),
			'currency'		=>$this->arrValOrNull($paytmResp,'CURRENCY'),
			'amount'		=>$this->arrValOrNull($paytmResp,'TXNAMOUNT'),
			'bank_ref_no'		=>$this->arrValOrNull($paytmResp,'BANKTXNID'),
			'payment_mode'		=>$this->arrValOrNull($paytmResp,'PAYMENTMODE'),
			'card_name'		=>$this->arrValOrNull($paytmResp,'BANKNAME')
		]);
				
		$redirectTo = "https://netbookflix.com/payment-failed";
		// check if the txn was successful
		if($paytmResp['STATUS']=="TXN_SUCCESS"){
			
			$Subscription_data=Subscription::where('transaction_id',$paytmResp['TXNID'])->first()->toArray();

			$Subscription_plan_data=Subscription_plan::find($Subscription_data['subscription_plan_id']);
			
			$user=Subscriber::where(['user_id'=>$Subscription_data['user_id'],'status'=>1])->first();
			if($user){
				
				//$date=new Carbon::parse($user->plan_end_date);
				$date = Carbon::parse($user->plan_end_date);
				$now = Carbon::now();
				if($now->diffInMinutes($date,false)<=0){
					// expired in the past, set expiry from now.
			 		$date = $now;
			 	}
			 	$user->plan_end_date = $date->addDays($Subscription_plan_data->validity);
				$user->save();
				
			}
			else{
				
				$subscriber=[
					'user_id' => $Subscription_data['user_id'],
					'subscription_id' => $Subscription_data['subscription_plan_id'],
					'plan_end_date' => $Date = Carbon::now()->addDays($Subscription_plan_data->validity)->toDateString(),
					'plan_name' => $Subscription_plan_data->name,
					'status'=>1
				];
				Subscriber::create($subscriber);
				
			}
			// delete the html file
			Storage::disk('public')->delete('pay/'.$paytmResp['ORDERID'].'.html');
			$redirectTo = "https://netbookflix.com/payment-sucess";

		}
		
        return Redirect::to($redirectTo);
    }







    public function cancelPayment(Request $request)
    {
        $response = Indipay::gateway('Paytm')->response($request);
        $order_id =    $response['order_id'];
        $tracking_id = $response['tracking_id'];
        $bank_ref_no = $response['bank_ref_no'];
        $order_status = $response['order_status'];
        $failure_message = $response['failure_message'];
        $payment_mode = $response['payment_mode'];
        $card_name = $response['card_name'];
        $status_code = $response['status_code'];
    
        $status_message = $response['status_message'];
        $currency = $response['currency'];
        $amount = $response['amount'];
        Subscription::where('transaction_id',$order_id)->update(['bank_ref_no'=>$bank_ref_no,'order_status'=>$order_status,'failure_message'=>$failure_message,'payment_mode'=>$payment_mode,'card_name'=>$card_name,'status_code'=>$status_code,'status_message'=>$status_message,'currency'=>$currency,'amount'=>$amount]);
        // return response()->json(['status' => '200', 'message' => 'Payment successfull','data'=>$subs]);
        //        return view('payment-status',compact('order_status')); 
	    $url = "https://netbookflix.com/payment-failed" ;
	        return Redirect::to($url);
    }
	

	
	
	
	
	
	
	
	
	
}
