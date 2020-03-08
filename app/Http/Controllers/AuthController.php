<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterNewRequest $request)
    {
       $field = $request->has('email') ? 'email' : 'mobile';
       $value = $request->get($field);
       $code = random_int(10,90). random_int(10,90) . random_int(10,90);

       Cache::put('user-auth-register-'.$value , compact('code','field'),config('auth.register-cache-expiration',14400));

       // TODO: Send email or sms (verification) to user
        Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER',['code' => $code]);
        return response(['message' => 'User temporary added!'],200);
    }

    public function registerVerify($code,$field)
    {
        $registerData = Cache::get('user-auth-register-'.$field);
        if($registerData && $registerData['code'] == $code){

        }else{

        }
    }
}
