<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    const CHANGE_EMAIL_CACHE_KEY = 'change.email.for.user.';

    public function changeEmail(ChangeEmailRequest $request){
        try {

            $email = $request->email;
            $userId = auth()->id();
            $code = random_verification_code();
            $expireDate = now()->addMinutes(config('auth.change_email_cache_expiration',1440));

            Cache::put(self::CHANGE_EMAIL_CACHE_KEY . $userId, compact('email', 'code'), $expireDate);

            //TODO: Send email to user for change email
            Log::info('SEND-CHANGE-EMAIL-CODE',compact('code'));
            return response([
                'message' => 'Change email verification sent. Check your Inbox or Spam!'
            ],200);
        }catch (\Exception $error){
            Log::error($error);
            return response(['message' => 'Something wrong went or server not respond!'],500);
        }
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request){

        $userId = auth()->id();
        $cacheKey = self::CHANGE_EMAIL_CACHE_KEY.$userId;
        $cache = Cache::get($cacheKey);


        if(empty($cache) ||  $cache['code'] != $request->code){
           return response([
               'message' => 'Something wrong went or undefined request!'
           ],400);
        }

        $user = auth()->user();

        $user->email = $cache['email'];
        $user->save();

        Cache::forget($cacheKey);


        return response([
            'message' => 'Email successfully changed!'
        ],200);

        dd($cache);


    }
}
