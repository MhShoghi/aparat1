<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'],function ($router){
    $router->post('login', [
        'as' => 'auth.login',
        'middleware' => ['throttle'],
        'uses' => 'AccessTokenController@issueToken'
    ]);
});

Route::post('register', [
    'as' => 'auth.register',
    'uses' => 'AuthController@register'
]);

Route::post('register-verify',[
    'as' => 'auth.register-verify',
    'uses' => 'AuthController@registerVerify'
]);


Route::post('resend-verification-code', [
    'as' => 'auth.register.resendVerificationCode',
    'uses' => 'AuthController@resendVerificationCode'
]);

Route::post('change-email', ['middleware' => ['auth:api'],
    'as' => 'change-email',
    'uses' => 'UserController@changeEmail'

]);

Route::post('change-email-submit', [
    'middleware' => ['auth:api'],
    'as' => 'change.email.submit',
    'uses' => 'UserController@changeEmailSubmit'
]);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
