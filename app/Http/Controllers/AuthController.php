<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Http\Requests\Auth\RegisterNewRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        $user = User::create([
            $field => $value,
            'verify_code' => $code,
        ]);

       // TODO: Send email or sms (verification) to user
        Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER',['code' => $code]);
        return response(['message' => 'Temporary user added!'],200);
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {

        $code = $request->code;

        $user = User::where('verify_code', $code)->first();


        if(empty($user)){
            throw new ModelNotFoundException('User not found with entered code');
        }

        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user,200);
    }
}
