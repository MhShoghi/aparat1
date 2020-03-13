<?php


namespace App\Services;


use App\Http\Requests\Channel\UpdateChannelRequest;

class ChannelService extends BaseService
{

    public static function updateChannelInfo(UpdateChannelRequest $request)
    {

        $channelId = $request->route('id');
        // TODO: Check is admin to update other user channel

        $channel = auth()->user()->channel;
        $channel->name = $request->name;
        $channel->info = $request->info;
        $channel->save();


        return $request->validated();
    }
}
