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



/** Auth routes */
Route::group([],function($router){
    Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'],function ($router){
        $router->post('login', [
            'as' => 'auth.login',
            'middleware' => ['throttle'],
            'uses' => 'AccessTokenController@issueToken'
        ]);
    });

    $router->post('register', [
        'as' => 'auth.register',
        'uses' => 'AuthController@register'
    ]);

    $router->post('register-verify',[
        'as' => 'auth.register-verify',
        'uses' => 'AuthController@registerVerify'
    ]);

    $router->post('resend-verification-code', [
        'as' => 'auth.register.resendVerificationCode',
        'uses' => 'AuthController@resendVerificationCode'
    ]);
});

/** User routes */
Route::group(['middleware' => ['auth:api']],function($router){

    $router->post('change-email', [
        'as' => 'change-email',
        'uses' => 'UserController@changeEmail'
    ]);

    $router->post('change-email-submit', [
        'as' => 'change.email.submit',
        'uses' => 'UserController@changeEmailSubmit'
    ]);

    $router->match(['post','put'], 'change-password', [
        'as' => 'password.change',
        'uses' => 'UserController@changePassword'
    ]);

    $router->group(['prefix' => '/user'], function($router){
        $router->match(['post', 'get'], '/{channel}/follow', [
            'as' => 'user.follow',
            'uses' => 'UserController@follow'
        ]);

        $router->match(['post', 'get'], '/{channel}/unfollow', [
            'as' => 'user.unfollow',
            'uses' => 'UserController@unfollow'
        ]);

        $router->get('/followings' , [
            'as' => 'user.followings',
            'uses' => 'UserController@followings'
        ]);

        $router->get('/followers', [
            'as' => 'user.followers',
            'uses' => 'UserController@followers'
        ]);
    });
});

/** Channel routes */
Route::group(['middleware' => ['auth:api'],'prefix'=>'/channel'],function ($router){
    $router->put('/{id?}',[
        'as' => 'channel.update',
        'uses' => 'ChannelController@update'
    ]);

    $router->match(['post','put'],'/',[
        'as' => 'channel.upload.avatar',
        'uses' => 'ChannelController@uploadBanner'
    ]);

    $router->match(['post','put'], '/socials', [
        'as' => 'channel.update.socials',
        'uses' => 'ChannelController@updateSocials'
    ]);

    $router->get('/statistics' , [
        'as' => 'channel.statistics',
        'uses' => 'ChannelController@statistics'
    ]);


});

/** Video routes */
Route::group(['middleware' => [], 'prefix' => '/video'] ,function ($router){

    $router->match(['get','post'],'/{video}/like', [
        'as' => 'video.like',
        'uses' => 'VideoController@likeVideo'
    ]);

    $router->match(['get','post'],'/{video}/dislike', [
        'as' => 'video.dislike',
        'uses' => 'VideoController@dislikeVideo'
    ]);

    $router->get('/',[
        'as' => 'video.list',
        'uses' => 'VideoController@getAll'
    ]);

    $router->get('/{video}',[
        'as' => 'video.show',
        'uses' => 'VideoController@showVideo'
    ]);

    // Routes that only logged-in users have access to
    Route::group(['middleware' => ['auth:api']], function ($router){
        $router->post('/upload', [
            'as' => 'video.upload',
            'uses' => 'VideoController@upload'
        ]);

        $router->post('/', [
            'as' => 'video.create',
            'uses' => 'VideoController@create'
        ]);

        $router->post('/upload-banner', [
            'as' => 'video.upload.banner',
            'uses' => 'VideoController@uploadBanner'
        ]);

        $router->put('/{video}/state', [
            'as' => 'video.change.state',
            'uses' => 'VideoController@changeState'
        ]);

        $router->post('/{video}/republish',[
            'as' => 'video.republish',
            'uses' => 'VideoController@republish'
        ]);

        $router->get('/liked', [
            'as' => 'video.liked',
            'uses' => 'VideoController@likedByCurrentUser'
        ]);



    });


});

/** Category routes */
Route::group(['middleware' => ['auth:api'] , 'prefix' => '/category'], function ($router){
    $router->get('/', [
        'as' => 'category.all',
        'uses' => 'CategoryController@index'
    ]);

    $router->get('/my', [
        'as' => 'category.my',
        'uses' => 'CategoryController@myCategory'
    ]);

    $router->post('/', [
        'as' => 'category.create',
        'uses' => 'CategoryController@createCategory'
    ]);

    $router->post('/upload-banner',[
        'as' => 'category.banner.upload',
        'uses' => 'CategoryController@uploadBanner'
    ]);

});

/** Playlist routes */
Route::group(['middleware' => ['auth:api'],'prefix' => '/playlist'], function ($router){
    $router->get('/',[
       'as' => 'playlist.all',
        'uses' => 'PlaylistController@getAll'
    ]);

    $router->get('/my',[
        'as' => 'playlist.my',
        'uses' => 'PlaylistController@getMy'
    ]);

    $router->post('/', [
        'as' => 'playlist.create',
        'uses' => 'PlaylistController@create'
    ]);

});

/** Tag routes */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/tag'], function ($router){
    $router->get('/', [
        'as' => 'tag.all',
        'uses' => 'TagController@getAll'
    ]);

    $router->post('/',[
        'as' => 'tag.create',
        'uses' => 'TagController@create'
    ]);
});

/** Comment routes */
Route::group(['middleware' => ['auth:api'], 'prefix'=> '/comment'], function ($router){
   $router->get('/',[
       'as' => 'comment.all',
       'uses' => 'CommentController@all'
   ]);

   $router->post('/', [
      'as' => 'comment.create',
      'uses' => 'CommentController@create'
   ]);

   $router->match(['post', 'put'], '/{comment}/state', [
       'as' => 'comment.change.state',
       'uses' => 'CommentController@changeState'
   ]);

   $router->delete('/{comment}',[
       'as' => 'comment.delete',
       'uses' => 'CommentController@delete'
   ]);
});


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
