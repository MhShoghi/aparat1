<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Services\ChannelService;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

    public function update(UpdateChannelRequest $request)
    {

        return ChannelService::updateChannelInfo($request);
    }

    public function uploadBanner(UploadBannerForChannelRequest $request)
    {
        return ChannelService::uploadAvatarForChannel($request);
    }

    public function updateSocials(UpdateSocialsRequest $request){
        return ChannelService::updateSocials($request);
    }
}
