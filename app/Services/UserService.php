<?php


namespace App\Services;


use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{

    public static function registerNewUser(RegisterNewRequest $request){
        try {
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();


            // If user has registered before , we stop register route
            if($user = User::where($field,$value)->first()){

                //if user has registered before , we should throw exception
                if($user->verified_at){
                    throw new UserAlreadyRegisteredException('Shoma ghablan sabte nam kardid !');
                }
                return response(['message' => 'Code faalsazi ghablan baraye shoma ersal shode ast'],200);
            }


            $code = random_verification_code();
            $user = User::create([
                $field => $value,
                'verify_code' => $code,
            ]);



            // TODO: Send email or sms (verification) to user
            Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER',['code' => $code]);


            DB::commit();
            return response(['message' => 'Temporary user added!'],200);

        }catch (\Exception $exception){

            DB::rollBack();

            if($exception instanceof UserAlreadyRegisteredException){
                throw $exception;
            }


            Log::error($exception);
            return response(['message' => 'Something wrong went']);
        }
    }

    public static function registerVerify(RegisterVerifyUserRequest $request)
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

    public static function resendVerificationCodeToUser(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field ,$value)->whereNull('verified_at')->first();

        if(!empty($user)){
            $dataDiff = now()->diffInMinutes($user->updated_at);
            if($dataDiff > config('auth.resend_verification_code_time_diff',60)){
                $user->verify_code = random_verification_code();
                $user->save();
            }

            // TODO: Send email or sms (verification) to user
            Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER',['code' => $user->verify_code]);

            return response([
                'message' => 'Verification code sent!'
            ], 200);
        }

        throw new ModelNotFoundException("User not found or before activated!");
    }
}
