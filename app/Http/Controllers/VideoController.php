<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\DislikeVideoRequest;
use App\Http\Requests\Video\GetAllPersonalVideoRequest;
use App\Http\Requests\Video\ChangeStateVideoRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\likedByCurrentUser;
use App\Http\Requests\Video\LikeVideoRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\ShowVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;

class VideoController extends Controller
{
    public function upload(UploadVideoRequest $request){
        return VideoService::upload($request);
    }

    public function uploadBanner(UploadVideoBannerRequest $request){
        return VideoService::uploadBanner($request);
    }

    public function create(CreateVideoRequest $request){
        return VideoService::create($request);
    }

    public function changeState(ChangeStateVideoRequest $request)
    {
        return VideoService::changeVideoState($request);
    }

    public function getAll(GetAllPersonalVideoRequest $request)
    {
        return VideoService::getAllVideo($request);
    }

    public function showVideo(ShowVideoRequest $request)
    {
        return VideoService::showVideo($request);
    }

    public function republish(RepublishVideoRequest $request)
    {
        return VideoService::republishVideo($request);
    }

    public function likeVideo(LikeVideoRequest $request)
    {
        return VideoService::likeVideo($request);
    }

    public function dislikeVideo(DislikeVideoRequest $request)
    {
        return VideoService::dislikeVideo($request);
    }

    public function likedByCurrentUser(likedByCurrentUser $request)
    {
        return VideoService::likedByCurrentUser($request);
    }

}
