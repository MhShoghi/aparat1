<?php


namespace App\Services;


use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Playlist;
use App\Tag;
use App\Video;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pbmedia\LaravelFFMpeg\FFMpegFacade;
use Pbmedia\LaravelFFMpeg\Media;

class VideoService extends BaseService
{

    /**
     * Upload video and move to temp folder
     * @param UploadVideoRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function upload(UploadVideoRequest $request)
    {

        try {
            $video = $request->file('video');
            $fileName = time() . Str::random(10);
            Storage::disk('videos')->put('/tmp/'.$fileName , $video->get());



            return response(['video' => $fileName],200);
        }catch (\Exception $exception){
            Log::error($exception);
            return response(['message' => 'Something wrong went!'],500);
        }
    }

    public static function uploadBanner(UploadVideoBannerRequest $request)
    {
        try {

            $banner = $request->file('banner');
            $fileName = time() . Str::random(10) . '-banner';

            Storage::disk('videos')->put('/tmp/'.$fileName, $banner->get());
            return response(['banner' => $fileName],200);
        }catch (\Exception $exception){
            Log::error($exception);
            return response(['message' => 'Error has occurred!']);
        }
    }

    public static function create(CreateVideoRequest $request)
    {

        try{

            /** @var Media $videoFile */
            $uploadedVideoPath = 'tmp/'.$request->video_id;
            $uploadedVideo = \FFM::fromDisk('videos')->open($uploadedVideoPath);

            $filter = new CustomFilter("drawtext=text='http\\://Refahshahrvand.com':fontfile=".env("FFMPEG_FONT_ADDRESS") . ":
            fontcolor=blue : fontsize=24 : box=1 : boxcolor=white@0.5 : boxborderw=5 :
            x=10 : y=(h - text_h - 10)");


            $format = new X264("libmp3lame");
            $videoFile = $uploadedVideo->addFilter($filter)
                ->export()
                ->toDisk('videos')
                ->inFormat($format);



            DB::beginTransaction();


            // Save video
            $video = Video::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'category_id' => $request->category,
                'channel_category_id' => $request->channel_category,
                'slug' => '',
                'info' => $request->info,
                'duration' => $uploadedVideo->getDurationInSeconds(), //Todo: get video length
                'banner' => $request->banner,
                'enable_comments' => $request->enable_comments,
                'publish_at' => $request->publish_at
            ]);

            // Generate unique slug from video id
            $video->slug = uniqueId($video->id);

            $video->banner = $video->slug . '-banner';
            $video->save();



            // Save video file and banner

            $videoFile->save(auth()->id() . '/' .$video->slug.'.mp4');
            Storage::disk('videos')->delete($uploadedVideoPath);
            if($request->banner){
                Storage::disk('videos')->move ('/tmp/'.$request->banner,auth()->id().'/'.$video->banner);
            }


            // assign playlist to video
            if($request->playlist){
                $playlist = Playlist::find($request->playlist);
                $playlist->videos()->attach($video->id);
            }

            // Sync tags
            if($request->tags){
                $video->tags()->attach($request->tags);
            }

            DB::commit();
            return response($video,200);
        }catch (\Exception $exception){
            Log::error($exception);

            dd($exception);
            DB::rollBack();
            return response([
                'message' => 'Error has occurred!'
            ],500);
        }
    }
}
