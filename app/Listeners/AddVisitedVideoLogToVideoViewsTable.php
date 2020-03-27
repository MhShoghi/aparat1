<?php

namespace App\Listeners;

use App\Events\VisitVideo;
use App\VideoView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddVisitedVideoLogToVideoViewsTable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param VisitVideo $event
     * @return void
     */
    public function handle(VisitVideo $event)
    {
        try {
            $video = $event->getVideo();

            $conditions = [
                'user_id' => auth('api')->id(),
                'video_id' => $video->id,
                ['created_at', '>', now()->subDays(1)]
            ];

            $client_ip = client_ip();

            if(!auth('api')->check()){
                $conditions['user_ip'] = $client_ip;
            }

            if(VideoView::where($conditions)->count() == 0){
                VideoView::create([
                    'user_id' => auth('api')->id(),
                    'video_id' => $video->id,
                    'user_ip' => $client_ip
                ]);
            }

        }catch (\Exception $exception){
            Log::error($exception);
        }
    }
}
