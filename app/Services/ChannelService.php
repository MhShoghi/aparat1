<?php


namespace App\Services;


use App\Channel;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChannelService extends BaseService
{

    public static function updateChannelInfo(UpdateChannelRequest $request)
    {

        try {

            if($channelId = $request->route('id')){

                if(auth()->user()->type != User::TYPE_ADMIN){
                    throw new AuthorizationException('You dont have permission!');
                }

                $channel = Channel::findOrFail($channelId);
                $user = $channel->user();
            }else{
                $user = auth()->user();
                $channel = $user->channel;
            }
            $user = auth()->user();
            // TODO: Check is admin to update other user channel

            DB::beginTransaction();

            $channel->name = $request->name;
            $channel->info = $request->info;
            $channel->save();

            $user->website = $request->website;
            $user->save();


            DB::commit();
            return response(['message' => 'Information successfully changed'],200);

        }catch (\Exception $exception){
            Log::error($exception);

            DB::rollBack();

            if($exception instanceof AuthorizationException){
                throw $exception;
            }
            return response(['message' => 'Something wrong went!'],500);

        }
    }
}
