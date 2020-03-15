<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Http\Requests\CreateVideoRequest;
use App\Http\Requests\UpdateSocialsRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\ChannelService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function upload(UploadVideoRequest $request){
        return VideoService::upload($request);
    }

    public function create(CreateVideoRequest $request){
        return VideoService::create($request);
    }
}
