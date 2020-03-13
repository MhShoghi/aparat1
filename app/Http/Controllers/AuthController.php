<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    /**
     * Register User
     * @param RegisterNewRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response

     */
    public function register(RegisterNewRequest $request)
    {
        return UserService::registerNewUser($request);
    }

    /**
     * Active User Verification Code
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        return UserService::registerVerify($request);
    }

    /**
     * Resend Verification code to user
     * @param ResendVerificationCodeRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resendVerificationCode(ResendVerificationCodeRequest $request){
        return UserService::resendVerificationCodeToUser($request);
    }
}
