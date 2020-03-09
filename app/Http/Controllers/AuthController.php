<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register User
     * @param RegisterNewRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws UserAlreadyRegisteredException
     */
    public function register(RegisterNewRequest $request)
    {
       $field = $request->has('email') ? 'email' : 'mobile';
       $value = $request->get($field);


        // If user has registered before , we stop register route
        if($user = User::where($field,$value)->first()){

            //if user has registered before , we should throw exception
            if($user->verified_at){
                throw new UserAlreadyRegisteredException('Shoma ghablan sabte nam kardid !');
            }
            return response(['message' => 'Code faalsazi ghablan baraye shoma ersal shode ast'],200);
        }


        $code = random_int(10,90). random_int(10,90) . random_int(10,90);
        $user = User::create([
               $field => $value,
               'verify_code' => $code,
           ]);



       // TODO: Send email or sms (verification) to user
        Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER',['code' => $code]);
        return response(['message' => 'Temporary user added!'],200);
    }

    /**
     * Active User Verification Code
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function registerVerify(RegisterVerifyUserRequest $request)
    {

        $code = $request->code;
        $field = $request->has('mobile') ? 'mobile' : 'email' ;
        $value = $request->get($field);

        $user = User::where([
            'verify_code' => $code,
            $field => $value
        ])->first();

        if(empty($user)){
            throw new ModelNotFoundException('User not found with entered code');
        }

        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user,200);
    }
}
