<?php


namespace App\Services;


use App\Channel;
use App\Comment;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Http\Requests\Channel\ChannelStatisticsRequest;
use App\Video;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChannelService extends BaseService
{

    public static function updateChannelInfo(UpdateChannelRequest $request)
    {

        try {

            if($channelId = $request->route('id')){
                $channel = Channel::findOrFail($channelId);
                $user = $channel->user();
            }else{
                $user = auth()->user();
                $channel = $user->channel;
            }
            $user = auth()->user();


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
            return response(['message' => 'Something wrong went!'],500);

        }
    }

    public static function uploadAvatarForChannel(UploadBannerForChannelRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName = md5(auth()->id());
            $banner->move(public_path('channel-banners'),$fileName);




            $channel = auth()->user()->channel;


            if($channel->banner){

                unlink(public_path($channel->banner));
            }

            $channel->banner = 'channel-banners/'. $fileName;
            $channel->save();


            return response(['banner' => url('channel-banners/'.$fileName)],200);
        }catch (\Exception $exception){
            Log::error($exception);

            return response(['message' => 'Something wrong went!!!'],500);
        }
    }

    public static function updateSocials(UpdateSocialsRequest $request)
    {
       try{
           $socials = [
               "cloob" => $request->input('cloob'),
               "lenzor" => $request->input('lenzor'),
               "facebook" => $request->input('facebook'),
               "telegram" => $request->input('telegram'),
               "twitter" => $request->input('twitter')
           ];

           $channel = auth()->user()->channel;
           $channel->update(['socials' => $socials]);


           return response(['message' => 'Successful!'],200);
       }catch (\Exception $exception){
           Log::error($exception);

           return response(['message' => 'Something wrong went! (update socials)'],500);
       }
    }

    public static function statistics(ChannelStatisticsRequest $request)
    {
        $data = [
            'views' => [],
            'total_views' => 0,
            'total_followers' => $request->user()->followers()->count(),
            'total_videos' => $request->user()->channelVideos()->count(),
            'total_comments' => Video::channelComments($request->user()->id)
                ->selectRaw('comments.*')->count()
            //TODO: Get count of unapproved comments
        ];


        /** @var Builder $videos */
        $videos = Video::views($request->user()->id)
            ->selectRaw('date(video_views.created_at) as date, count(*) as views')
            ->groupBy(DB::raw('date(video_views.created_at)'))
            ->get()
            ->each(function ($item) use (&$data) {
            $data['total_views'] += $item->views;
            $data['views'][$item->date] = $item->views;
        });

        return $data;
    }


}
