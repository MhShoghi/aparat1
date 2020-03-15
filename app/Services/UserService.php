<?php


namespace App\Services;


use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{

    const CHANGE_EMAIL_CACHE_KEY = 'change.email.for.user.';

    public static function registerNewUser(RegisterNewRequest $request)
    {
        try {
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();


            // If user has registered before , we stop register route
            if ($user = User::where($field, $value)->first()) {

                //if user has registered before , we should throw exception
                if ($user->verified_at) {
                    throw new UserAlreadyRegisteredException('Shoma ghablan sabte nam kardid !');
                }
                return response(['message' => 'Code faalsazi ghablan baraye shoma ersal shode ast'], 200);
            }


            $code = random_verification_code();
            $user = User::create([
                $field => $value,
                'verify_code' => $code,
            ]);


            // TODO: Send email or sms (verification) to user
            Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);


            DB::commit();
            return response(['message' => 'Temporary user added!'], 200);

        } catch (\Exception $exception) {

            DB::rollBack();

            if ($exception instanceof UserAlreadyRegisteredException) {
                throw $exception;
            }


            Log::error($exception);
            return response(['message' => 'Something wrong went']);
        }
    }

    public static function registerVerify(RegisterVerifyUserRequest $request)
    {

        $code = $request->code;
        $field = $request->has('mobile') ? 'mobile' : 'email';
        $value = $request->get($field);

        $user = User::where([
            'verify_code' => $code,
            $field => $value
        ])->first();

        if (empty($user)) {
            throw new ModelNotFoundException('User not found with entered code');
        }

        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user, 200);
    }

    public static function resendVerificationCodeToUser(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->whereNull('verified_at')->first();

        if (!empty($user)) {
            $dataDiff = now()->diffInMinutes($user->updated_at);
            if ($dataDiff > config('auth.resend_verification_code_time_diff', 60)) {
                $user->verify_code = random_verification_code();
                $user->save();
            }

            // TODO: Send email or sms (verification) to user
            Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $user->verify_code]);

            return response([
                'message' => 'Verification code sent!'
            ], 200);
        }

        throw new ModelNotFoundException("User not found or before activated!");
    }

    public static function changeEmail(ChangeEmailRequest $request)
    {
        try {

            $email = $request->email;
            $userId = auth()->id();
            $code = random_verification_code();
            $expireDate = now()->addMinutes(config('auth.change_email_cache_expiration', 1440));

            Cache::put(self::CHANGE_EMAIL_CACHE_KEY . $userId, compact('email', 'code'), $expireDate);

            //TODO: Send email to user for change email
            Log::info('SEND-CHANGE-EMAIL-CODE', compact('code'));
            return response([
                'message' => 'Change email verification sent. Check your Inbox or Spam!'
            ], 200);
        } catch (\Exception $error) {
            Log::error($error);
            return response(['message' => 'Something wrong went or server not respond!'], 500);
        }
    }

    public static function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        $userId = auth()->id();
        $cacheKey = self::CHANGE_EMAIL_CACHE_KEY . $userId;
        $cache = Cache::get($cacheKey);


        if (empty($cache) || $cache['code'] != $request->code) {
            return response([
                'message' => 'Something wrong went or undefined request!'
            ], 400);
        }

        $user = auth()->user();

        $user->email = $cache['email'];
        $user->save();

        Cache::forget($cacheKey);


        return response([
            'message' => 'Email successfully changed!'
        ], 200);

    }

    public static function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();

            if (!Hash::check($request->old_password, $user->password)) {
                return response(['message' => 'Passwords dont match'],400);
            }

            $user->update(['password' => bcrypt($request->new_password)]);

            return $user->password;
            return response(['message' => 'Password changed successfully!'],200);

        }catch (\Exception $exception){
            Log::error($exception);
            return response(['message' => 'Something wrong went! (change password)'],500);
        }
    }
}

