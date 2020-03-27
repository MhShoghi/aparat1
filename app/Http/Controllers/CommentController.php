<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\ChangeCommentStateRequest;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\GetAllCommentsRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function all(GetAllCommentsRequest $request)
    {
        return CommentService::getAllComments($request);
    }

    public function create(CreateCommentRequest $request)
    {
        return CommentService::createComment($request);
    }

    public function changeState(ChangeCommentStateRequest $request)
    {
        return CommentService::changeState($request);
    }

    public function delete(DeleteCommentRequest $request)
    {
        return CommentService::deleteComment($request);
    }
}
