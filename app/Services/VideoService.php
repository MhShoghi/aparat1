<?php


namespace App\Services;


use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
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
use App\Playlist;
use App\Video;
use App\VideoFavourites;
use App\VideoRepublish;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            Storage::disk('videos')->put('/tmp/' . $fileName, $video->get());


            return response(['video' => $fileName], 200);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'Something wrong went!'], 500);
        }
    }

    public static function uploadBanner(UploadVideoBannerRequest $request)
    {
        try {

            $banner = $request->file('banner');
            $fileName = time() . Str::random(10) . '-banner';

            Storage::disk('videos')->put('/tmp/' . $fileName, $banner->get());
            return response(['banner' => $fileName], 200);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'Error has occurred!']);
        }
    }

    public static function create(CreateVideoRequest $request)
    {

        try {

            DB::beginTransaction();

            // Save video
            $video = Video::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'category_id' => $request->category,
                'channel_category_id' => $request->channel_category,
                'slug' => '',
                'info' => $request->info,
                'duration' => 0,
                'banner' => $request->banner,
                'enable_comments' => $request->enable_comments,
                'publish_at' => $request->publish_at,
                'state' => Video::STATE_PENDING
            ]);

            // Generate unique slug from video id
            $video->slug = uniqueId($video->id);

            $video->banner = $video->slug . '-banner';
            $video->save();


            // Save video file and banner
            event(new UploadNewVideo($video, $request));

            if ($request->banner) {
                Storage::disk('videos')->move('/tmp/' . $request->banner, auth()->id() . '/' . $video->banner);
            }

            // assign playlist to video
            if ($request->playlist) {
                $playlist = Playlist::find($request->playlist);
                $playlist->videos()->attach($video->id);
            }

            // Sync tags
            if ($request->tags) {
                $video->tags()->attach($request->tags);
            }

            DB::commit();
            return response($video, 200);
        } catch (\Exception $exception) {
            Log::error($exception);


            DB::rollBack();
            return response([
                'message' => 'Error has occurred!'
            ], 500);
        }
    }

    public static function changeVideoState(ChangeStateVideoRequest $request)
    {

        $video = $request->video;
        $video->state = $request->state;
        $video->save();
        return response([$video], 200);
    }

    public static function getAllVideo(GetAllPersonalVideoRequest $request)
    {

        $user = auth('api')->user();

        if ($request->has('republished')) {
            if ($user) {
                $videos = $request->republished ? $user->republishVideos() : $user->channelVideos();
            }
            else{
                $videos = $request->republished ? Video::whereRepublished() : Video::whereNotRepublished();
            }
        } else {
                $videos = $user ? $user->videos() : Video::query();

        }

        return $videos->orderBy('id')->paginate(10);

    }

    public static function showVideo(ShowVideoRequest $request)
    {

        event(new VisitVideo($request->video));
        return $request->video;
    }

    public static function republishVideo(RepublishVideoRequest $request)
    {
        try {

            VideoRepublish::create([
                'user_id' => auth()->id(),
                'video_id' => $request->video->id
            ]);

            return response(['message' => 'republish successfully!'], 200);


        } catch (\Exception $exception) {
            Log::error($request);
            return response(['message' => 'Something wrong went! Try again'], 500);
        }
    }

    public static function likeVideo(LikeVideoRequest $request)
    {

            VideoFavourites::create([
                'user_id' => auth('api')->id(),
                'video_id' => $request->video->id,
                'user_ip' => client_ip()

            ]);


        return response(['message' => 'Successful like!'], 200);

    }

    public static function dislikeVideo(DislikeVideoRequest $request)
    {
        VideoFavourites::where([
            'user_id' => auth('api')->id(),
            'video_id' => $request->video->id,
            'user_ip' => client_ip()
        ])->delete();

        return response(['message' => 'Successful dislike1'],200);
    }

    public static function likedByCurrentUser(likedByCurrentUser $request)
    {

        $user = auth()->user();
        $videos = $user->favouriteVideos()->paginate();
        return $videos;
    }
}
