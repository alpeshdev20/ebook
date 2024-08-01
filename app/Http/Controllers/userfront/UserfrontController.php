<?php

namespace App\Http\Controllers\userfront;

use App\Http\Controllers\Controller;
use App\model\ULogin;
use Hash;
use Google_Client;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Validator;
use App\Models\Subscriber;
use App\ExternalApp;
use JWTAuth;
use Auth;
use Illuminate\Support\Facades\Redirect;

class UserfrontController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => $error->errors()->all()], 200);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = ULogin::where('email', $email)->get();

        if (count($user) < 1) {
            return response()->json(['status' => 'false', 'message' => 'Invalid Email']);
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $user, 'access_token' => $token]);        

    }
    
    public function getSubsInfo(Request $request)
    {
        // return auth('api')->user()->id;
        $user = JWTAuth::parseToken()->authenticate();
        if($user) {
                $data = Subscriber::where('user_id', $user->id)->get();                    
                return response()->json(['data'=>$data,'status'=>'true','error'=>'','message'=>''], 200);       
            }
        return response()->json(['data'=>'','status'=>'false','error'=>'Invalid token provided','message'=>''], 200);   
    }

    public function register(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:u_logins',
            'password' => 'required',
            'mobile' => 'required|unique:u_logins',
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => $error->errors()->all()], 200);
        }

        $user = ULogin::where('email', $request->email)->get();
        $user2 = ULogin::where('mobile', $request->mobile)->get();

        if ((count($user) < 1) && (count($user2) < 1)) {
            $formdata = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,

            ];

            $data = ULogin::create($formdata);
            $user = ULogin::where('email', $request->email)->first();
            $accessToken = $user->createToken('authToken')->accessToken;
            return response()->json(['status' => 'true', 'message' => 'Registration Successfull', 'data' => $user, 'access_token' => $accessToken], 200);
        } else {
            return response()->json(['status' => 'false', 'message' => 'User Exists']);
        }
    }

    public function UpdateProfile(Request $request, $id)
    {

        $rules = [
            'name' => 'required',
            'mobile' => 'required',

        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => '500', 'error' => $error->errors()->all()]);
        }

        $formdata = [
            'name' => $request->name,
            'mobile' => $request->mobile,

        ];
        ULogin::whereId($id)->update($formdata);
        $newdata = ULogin::where('id', $id)->first();
        return response()->json(['status' => '200', 'message' => 'the user profile is Updated Successfully', 'data' => $newdata]);
    }

    public function google(Request $request)
    {
        $rules = [
            'token' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => 'missing token', 'data' => '', 'message' => ''], 200);
        }

        $CLIENT_ID = "148321591078-vqcdg37d41tmo07775jo0ca5ek4r3oog.apps.googleusercontent.com";
        $client = new Google_Client(['client_id' => $CLIENT_ID]);
        $id_token = $request->token;
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $email = $payload['email'];
            $findUser = ULogin::where('email', $email)->first();
            if ($findUser) {
                $user = ULogin::where('email', $email)->first();

                $users = ULogin::where('email', $email)->get();
                if (count($users) < 1) {
                    return response()->json(['status' => 'false', "message" => "Invalid Login", 'data' => '', 'error' => 'Email/Password not valid'], 200);
                }
                $old_pass = $user['password'];
                $datum = array();
                $datum['email'] = $email;
                $datum['password'] = 'dnsajcbbfwebfjb4r4uofje35t05335tjrruhr';
                // $datum['password'] =$user['password'];
                $credentials = $datum;

                $formdata = [
                    "password" => Hash::make("dnsajcbbfwebfjb4r4uofje35t05335tjrruhr"),
                ];

                ULogin::whereId($user['id'])->update($formdata);

                $formdata = [
                    "password" => $old_pass,
                ];

                ULogin::whereId($user['id'])->update($formdata);
                // $credentials = request(['email', 'password']);
                if (!$token = auth('api')->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $user, 'access_token' => $token]);
                // $accessToken = $user->createToken('authToken')->accessToken;
                // return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $user, 'access_token' => $accessToken]);

                return response()->json(['status' => 'true', "message" => "User has a password set, need password to login", 'data' => '', 'error' => 'Password Authenticated User'], 200);

            } else {
                $string = Str::random(64);
                $formdata = [
                    'name' => $payload['given_name'] . " " . $payload['family_name'],
                    'email' => $payload['email'],
                    'password' => Hash::make('dnsajcbbfwebfjb4r4uofje35t05335tjrruhr'),
                    'mobile' => '00000'
                ];
                ULogin::create($formdata);
                $email = $payload['email'];
                $data = ULogin::where('email', $email)->first();

                $datum['email'] = $payload['email'];
                $datum['password'] = 'dnsajcbbfwebfjb4r4uofje35t05335tjrruhr';
                $credentials = $datum;
                // $credentials = request(['email', 'password']);
                if (!$token = auth('api')->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $data, 'access_token' => $token]);
                // $accessToken = $data->createToken('authToken')->accessToken;
                // return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $data, 'access_token' => $accessToken]);
            }
        } else {
            return response()->json(['status' => 'false', 'error' => 'invalid token', 'data' => '', 'message' => ''], 200);
        }
    }

    public function facebook(Request $request)
    {
        $rules = [
            'token' => 'required'
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => 'missing token', 'data' => '', 'message' => ''], 200);
        }

        $url = "https://graph.facebook.com/me?fields=email,name&access_token=" . $request->token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);

        $payload = json_decode($server_output);

        if (isset($payload->error)) {
            return $this->sendError('Invalid Token');
        }

        if ($payload) {
            $payload = json_decode(json_encode($payload), true);
            if (!isset($payload["email"])) {
                return response()->json(['status' => 'false', "message" => "Invalid Login", 'data' => '', 'error' => 'Email disabled'], 200);
            }
            $email = $payload['email'];
            $findUser = ULogin::where('email', $email)->first();
            if ($findUser) {
                $user = ULogin::where('email', $email)->first();

                $users = ULogin::where('email', $email)->get();
                if (count($users) < 1) {
                    return response()->json(['status' => 'false', "message" => "Invalid Login", 'data' => '', 'error' => 'Email/Password not valid'], 200);
                }
                $old_pass = $user['password'];
                $datum = array();
                $datum['email'] = $email;
                // $datum['password'] = 'dnsajcbbfwebfjb4r4uofje35t05335tjrruhr';
                $datum['password'] = $old_pass;
                $credentials = $datum;

                // $formdata = [
                //     "password" => Hash::make("dnsajcbbfwebfjb4r4uofje35t05335tjrruhr"),
                // ];

                // ULogin::whereId($user['id'])->update($formdata);

                $formdata = [
                    "password" => $old_pass,
                ];

                ULogin::whereId($user['id'])->update($formdata);
                $credentials = $datum;
                // $credentials = request(['email', 'password']);
                if (!$token = auth('api')->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $user, 'access_token' => $token]);
                // $accessToken = $user->createToken('authToken')->accessToken;
                // return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $user, 'access_token' => $accessToken]);

                return response()->json(['status' => 'true', "message" => "User has a password set, need password to login", 'data' => '', 'error' => 'Password Authenticated User'], 200);

            } else {
                $string = Str::random(64);
                $nameData = explode(" ", $payload['name']);
                if (count($nameData) < 1) {
                    $nameData[1] = "";
                }
                $formdata = [
                    'name' => $nameData[0] . " " . $nameData[1],
                    'email' => $payload['email'],
                    'password' => Hash::make('dnsajcbbfwebfjb4r4uofje35t05335tjrruhr'),
                    'mobile' => '00000'
                ];
                ULogin::create($formdata);
                $email = $payload['email'];
                $data = ULogin::where('email', $email)->first();

                $datum['email'] = $payload['email'];
                $datum['password'] = 'dnsajcbbfwebfjb4r4uofje35t05335tjrruhr';
                $credentials = $datum;
                // $credentials = request(['email', 'password']);
                if (!$token = auth('api')->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $data, 'access_token' => $token]);
                // $accessToken = $data->createToken('authToken')->accessToken;
                // return response()->json(['status' => '200', 'message' => 'login successfuly', 'data' => $data, 'access_token' => $accessToken]);
            }
        } else {
            return response()->json(['status' => 'false', 'error' => 'invalid token', 'data' => '', 'message' => ''], 200);
        }
    }

    public function authCheckForExternalLogin(Request $request)
    {
        $ip = $request->header('origin');

        if($ip == '') {
            $ip = $this->getIP();
        }
        $public_key = $request->header('public-key');
        if($public_key == '') {
            return response()->json(['status' => 'false', 'error' => 'invalid request', 'data' => '', 'message' => ''], 200);            
        }
        $data = ExternalApp::where('url', $ip)->where('public_key', $public_key)->first();
        if(!$data) {
            return response()->json(['status' => 'false', 'error' => 'invalid request', 'data' => '', 'message' => ''], 200);                        
        }

        $input = $request->all();
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required'
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => 'missing token', 'data' => '', 'message' => ''], 200);
        }

        $externalContent = file_get_contents('http://checkip.dyndns.com/');
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
        $externalIp = $m[1];
        $liveUrl = 'https://netbookflix.com';
        if($externalIp == '65.1.3.203') {
            $liveUrl = 'https://nbf-staging.netlify.app';
        }

        $checkExistingUser= \App\Ulogin::where('email', $input['email'])->first();
        if($checkExistingUser){            

            if (!$token =Auth::guard('api')->login($checkExistingUser)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // $url='https://netbookflix.com/access_token/'.$token;
            return response()->json(['status' => 'success', 'message' => 'login successfuly','url' => $liveUrl.'/access_token/'.$token]);
            // return Redirect::to($url);
        }else{
            // $string = Str::random(64);
            $nameData = explode(" ", $input['name']);
            if (count($nameData) < 1) {
                $nameData[1] = "";
            }
            $formdata = [
                'name' => $nameData[0] . " " . $nameData[1],
                'email' => $input['email'],
                'password' => Hash::make('dnsajcbbfwebfjb4r4uofje35t05335tjrruhr'),
                'mobile' => $input['mobile']
            ];
            ULogin::create($formdata);
            $email = $input['email'];
            $data = ULogin::where('email', $email)->first();

            $datum['email'] = $input['email'];
            $datum['password'] = 'dnsajcbbfwebfjb4r4uofje35t05335tjrruhr';
            $credentials = $datum;
            // $credentials = request(['email', 'password']);
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return response()->json(['status' => 'success', 'message' => 'New Registration & login successfuly','url' => $liveUrl.'/access_token/'.$token]);
            // $url='https://netbookflix.com/access_token/'.$token;
            // return Redirect::to($url);

        }
    }

    public function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if (validate_ip($ip)) {
                        return $ip;
                    }

                }
            } else {
                if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
                }

            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED'])) {
            echo $_SERVER['HTTP_X_FORWARDED'];
            return $_SERVER['HTTP_X_FORWARDED'];
        }
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            echo $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR'])) {
            echo $_SERVER['HTTP_FORWARDED_FOR'];
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }
        if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED'])) {
            echo $_SERVER['HTTP_FORWARDED'];
            return $_SERVER['HTTP_FORWARDED'];
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($ip == "127.0.0.1") {
            $ip = "14.99.62.86";
        }
        return $ip;
    }

    public function validate_ip($ip)
    {
        if (strtolower($ip) === 'unknown') {
            return false;
        }

        $ip = ip2long($ip);

        if ($ip !== false && $ip !== -1) {
            $ip = sprintf('%u', $ip);
            if ($ip >= 0 && $ip <= 50331647) {
                return false;
            }

            if ($ip >= 167772160 && $ip <= 184549375) {
                return false;
            }

            if ($ip >= 2130706432 && $ip <= 2147483647) {
                return false;
            }

            if ($ip >= 2851995648 && $ip <= 2852061183) {
                return false;
            }

            if ($ip >= 2886729728 && $ip <= 2887778303) {
                return false;
            }

            if ($ip >= 3221225984 && $ip <= 3221226239) {
                return false;
            }

            if ($ip >= 3232235520 && $ip <= 3232301055) {
                return false;
            }

            if ($ip >= 4294967040) {
                return false;
            }

        }
        return true;
    }

}
