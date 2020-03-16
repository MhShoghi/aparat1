<?php


namespace App\Services;


use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
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
            $path = public_path('videos/tmp');
            $video->move($path,$fileName);
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
            $path = public_path('videos/tmp');

            $banner->move($path,$fileName);

            return response(['banner' => $fileName],200);
        }catch (\Exception $exception){
            Log::error($exception);
            return response(['message' => 'Error has occurred!']);
        }
    }

    public static function create(CreateVideoRequest $request)
    {
//        Storage::disk('videos')->copy("/tmp/{$request->video_id}",auth()->id() .'/'. $request->video_id);

        dd(Storage::disk('videos'));
        dd($request->validated());
    }
}
