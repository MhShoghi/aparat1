<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * Change user email
     * @param ChangeEmailRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeEmail(ChangeEmailRequest $request){

        return UserService::changeEmail($request);
    }

    /**
     * Submit change user email
     * @param ChangeEmailSubmitRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeEmailSubmit(ChangeEmailSubmitRequest $request){

        return UserService::changeEmailSubmit($request);

    }
}
