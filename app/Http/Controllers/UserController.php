<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\FollowerUserRequest;
use App\Http\Requests\User\FollowingUserRequest;
use App\Http\Requests\User\FollowUserRequest;
use App\Http\Requests\User\UnfollowUserRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function changeEmail(ChangeEmailRequest $request){

        return UserService::changeEmail($request);
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request){

        return UserService::changeEmailSubmit($request);

    }

    public function changePassword(ChangePasswordRequest $request){
        return UserService::changePassword($request);
    }

    public function follow(FollowUserRequest $request)
    {
        return UserService::followChannel($request);
    }

    public function unfollow(UnfollowUserRequest $request)
    {
        return UserService::unfollowChannel($request);
    }

    public function followings(FollowingUserRequest $request)
    {
        return UserService::followings($request);
    }

    public function followers(FollowerUserRequest $request)
    {
        return UserService::followers($request);
    }
}
