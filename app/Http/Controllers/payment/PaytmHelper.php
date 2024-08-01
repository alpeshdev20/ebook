<?php

namespace App\Http\Controllers\payment;

class PaytmHelper
{
	
	private static $iv = "@@@@&&&&####$$$$";
	

	static private function generateSignatureByString($params, $key){
		$salt = self::generateRandomString(4);
		return self::calculateChecksum($params, $key, $salt);
	}

	static private function verifySignatureByString($params, $key, $checksum){
		$paytm_hash = self::decrypt($checksum, $key);
		$salt = substr($paytm_hash, -4);
		return $paytm_hash == self::calculateHash($params, $salt) ? true : false;
	}

	static private function generateRandomString($length) {
		$random = "";
		srand((double) microtime() * 1000000);

		$data = "9876543210ZYXWVUTSRQPONMLKJIHGFEDCBAabcdefghijklmnopqrstuvwxyz!@#$&_";	

		for ($i = 0; $i < $length; $i++) {
			$random .= substr($data, (rand() % (strlen($data))), 1);
		}

		return $random;
	}

	static private function getStringByParams($params) {
		ksort($params);		
		$params = array_map(function ($value){
			return ($value !== null && strtolower($value) !== "null") ? $value : "";
	  	}, $params);
		return implode("|", $params);
	}

	static private function calculateHash($params, $salt){
		$finalString = $params . "|" . $salt;
		$hash = hash("sha256", $finalString);
		return $hash . $salt;
	}

	static private function calculateChecksum($params, $key, $salt){
		$hashString = self::calculateHash($params, $salt);
		return self::encrypt($hashString, $key);
	}

	static private function pkcs5Pad($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	static private function pkcs5Unpad($text) {
		$pad = ord($text[strlen($text) - 1]);
		if ($pad > strlen($text))
			return false;
		return substr($text, 0, -1 * $pad);
	}
	
	//public
	static public function encrypt($input, $key) {
		$key = html_entity_decode($key);

		if(function_exists('openssl_encrypt')){
			$data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, self::$iv );
		} else {
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
			$input = self::pkcs5Pad($input, $size);
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
			mcrypt_generic_init($td, $key, self::$iv);
			$data = mcrypt_generic($td, $input);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			$data = base64_encode($data);
		}
		return $data;
	}

	static public function decrypt($encrypted, $key) {
		$key = html_entity_decode($key);
		
		if(function_exists('openssl_decrypt')){
			$data = openssl_decrypt ( $encrypted , "AES-128-CBC" , $key, 0, self::$iv );
		} else {
			$encrypted = base64_decode($encrypted);
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
			mcrypt_generic_init($td, $key, self::$iv);
			$data = mdecrypt_generic($td, $encrypted);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			$data = self::pkcs5Unpad($data);
			$data = rtrim($data);
		}
		return $data;
	}

	static public function generateSignature($params, $key) {
		if(!is_array($params) && !is_string($params)){
			throw new Exception("string or array expected, ".gettype($params)." given");			
		}
		if(is_array($params)){
			$params = self::getStringByParams($params);			
		}
		return self::generateSignatureByString($params, $key);
	}

	static public function verifySignature($params, $key, $checksum){
		if(!is_array($params) && !is_string($params)){
			throw new Exception("string or array expected, ".gettype($params)." given");
		}
		if(isset($params['CHECKSUMHASH'])){
			unset($params['CHECKSUMHASH']);
		}
		if(is_array($params)){
			$params = self::getStringByParams($params);
		}		
		return self::verifySignatureByString($params, $key, $checksum);
	}
	
	static public function getTransactionId($txnDetails,$merchantKey,$isStaging=true){
		$res=array();
		$res['status'] = 'error';
		try{
			$paytmParams = array();
			$paytmParams["body"] = array(
				"requestType"   => "Payment",
				"mid"           => $txnDetails['MID'],
				"websiteName"   => (($isStaging)?"WEBSTAGING":"DEFAULT"),
				"orderId"       => $txnDetails['ORDER_ID'],
				"callbackUrl"   => $txnDetails['CALLBACK_URL'],
				"txnAmount"     => array(
					"value"     => $txnDetails['TXN_AMOUNT'],
					"currency"  => "INR",
				),
				"userInfo"      => array(
					"custId"    => $txnDetails['CUST_ID'],
					"firstName" => $txnDetails['CUST_NAME'],
					"email"     => $txnDetails['CUST_EMAIL'],
					"mobile"    => $txnDetails['CUST_PHONE'],
				),
				
			);
			$checksum = self::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchantKey);
			$paytmParams["head"] = array(
				"signature"    => $checksum,
				"channelId"    => 'WEB',
			);
			
			
			if($isStaging){
				// for Staging 
				$url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid={$txnDetails['MID']}&orderId={$txnDetails['ORDER_ID']}";
			}else{
				// for Production
				$url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid={$txnDetails['MID']}&orderId={$txnDetails['ORDER_ID']}";
			}
			//$paytmParams["head"]['URL']=$url;
			//$paytmParams["head"]['MK']=$merchantKey;
			$pro = self::processPaytm($url,$paytmParams);
			$pro=json_decode($pro);

			if(intval($pro->body->resultInfo->resultCode)>0) 
					throw new \Exception("Payment Gateway Error: ".$pro->body->resultInfo->resultCode.". ".$pro->body->resultInfo->resultMsg);
			$data = array(
				'CHECKSUMHASH'=>$pro->head->signature,
				'TXN_TOKEN'=>$pro->body->txnToken,
				'ORDER_ID'=>$txnDetails['ORDER_ID'],
				'MID'=>$txnDetails['MID'],
				'TXN_URL'=>(($isStaging)
								?"https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage?mid={$txnDetails['MID']}&orderId={$txnDetails['ORDER_ID']}"
								:"https://securegw.paytm.in/theia/api/v1/showPaymentPage?mid={$txnDetails['MID']}&orderId={$txnDetails['ORDER_ID']}"
							)
			);
			$res['status'] = 'ok';
			$res['data'] = $data;
			
		}catch(\Exception $ex){
			$res['data'] = $ex->getMessage();
		}
		return $res;
	}
	
	public static function processTransaction($params)
	{
		$res=array();
		$res['status'] = 'error';
		try{
			
			$paytmParams = array();
			$paytmParams["body"] = array(
				"requestType" => "NATIVE",
				"mid"         => $params['MID'],
				"orderId"     => $params['ORDER_ID'],
				"paymentMode" => "CREDIT_CARD",
			);

			$paytmParams["head"] = array(
				"channelId"    => 'WEB',
				"txnToken"    => $params['TXN_TOKEN']
			);
			
			if($isStaging){
				// for Staging 
				$url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid={$params['MID']}&orderId={$params['ORDER_ID']}";
			}else{
				// for Production
				$url = "https://securegw.paytm.in/theia/api/v1/processTransaction?mid={$params['MID']}&orderId={params['ORDER_ID']}";
			}

			$pro = self::processPaytm($url,$paytmParams);
			$res['status'] = 'ok';
			$res['data'] = $pro;
		
		}catch(\Exception $ex){
			$res['data'] = $ex->getMessage();
		}
		return $res;

		
	}
	
	
		
	/*
		MID
		MERCHANT_KEY
		ORDER_ID
		CALLBACK_URL
		FREQUENCY--according to plan choses, 365 or 30
		TXN_AMOUNT
		CUST_ID
		CUST_NAME
		CUST_EMAIL
		CUST_PHONE		
	*/
	

	
	public static function createSubsciptionRequest($params, $merchantKey, $isStaging=true){
		
		$res=array();
		$res['status'] = 'error';
		$orderId = "ORDERID_".mt_rand();
		try{
		
			$paytmParams = array();
			$paytmParams["body"] = array(
				"requestType"               => "NATIVE_SUBSCRIPTION",
				"mid"                       => $params['MID'],
				"websiteName"               => "WEBSTAGING",//(($isStaging)?"WEBSTAGING":"DEFAULT"),
				"orderId"                   => $orderId,//$params['ORDER_ID'],
				"callbackUrl"               => $params['CALLBACK_URL'],
				"subscriptionAmountType"    => "FIX",
				"subscriptionFrequency"     => "{$params['FREQUENCY']}",
				"subscriptionFrequencyUnit" => "DAY",
				"subscriptionStartDate"    	=> date('Y-m-d'),
				"subscriptionExpiryDate"    => "2023-05-20",
				/*"subscriptionEnableRetry"   => "1",*/
				"subscriptionGraceDays"   	=> "7", // 1 week
				/*"communicationManager"   	=> true,*/
				"txnAmount"                 => array(
					"value"                 => $params['TXN_AMOUNT'],
					"currency"              => "INR"
				),
				"userInfo"                  => array(
					"custId"                => $params['CUST_ID'],
					"firstName"             => $params['CUST_NAME'],
					"email"                	=> $params['CUST_EMAIL'],
					"mobile"                => $params['CUST_PHONE'],
				),
			);
			$checksum = self::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchantKey);
			$paytmParams["head"] = array(
				"signature"	              => $checksum,
				"channelId"	              => (isset($params['CHANNEL_ID'])?$params['CHANNEL_ID']:'WEB'),			
			);
			
			if($isStaging){
				// for Staging 
				$url = "https://securegw-stage.paytm.in/subscription/create?mid={$params['MID']}&orderId=$orderId";
			}else{
				// for Production
				$url = "https://securegw.paytm.in/subscription/create?mid={$params['MID']}&orderId={$params['ORDER_ID']}";
			}
			
			// curl paytm api	
			$pro = self::processPaytm($url,$paytmParams);
			$res['data'] = $pro;
			$res['status'] = 'ok';
			
		}catch(\Exception $ex){
			$res['data'] = $ex->getMessage();
		}
		return $res;
	}
	
	
	
	private static function processPaytm($url,$params)
	{
		
		$post_data = json_encode($params, JSON_UNESCAPED_SLASHES);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
		$exec = curl_exec($ch);
		if(!$exec || curl_errno($ch))throw new \Exception('Error('.curl_errno($ch).') '.curl_error($ch));
		return $exec;
		//print_r($response);
	}
	
	
}

?>
