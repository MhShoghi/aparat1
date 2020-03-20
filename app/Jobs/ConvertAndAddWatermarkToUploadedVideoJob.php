<?php

namespace App\Jobs;

use App\Video;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Pbmedia\LaravelFFMpeg\Media;

class ConvertAndAddWatermarkToUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Video
     */
    private $video;

    /**
     * @var Request
     */
    private $request;
    private $videoId;

    /**
     * @var int|string|null
     */
    private $userId;
    /**
     * @var bool
     */
    private $addWatermark;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @param string $videoId
     * @param bool $addWatermark
     */
    public function __construct(Video $video ,string $videoId, bool $addWatermark)
    {
        $this->video = $video;
        $this->videoId = $videoId;
        $this->userId = auth()->id();
        $this->addWatermark = $addWatermark;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $video = $this->video;

        $uploadedVideoPath = 'tmp/'.$this->videoId;
        $videoUploaded = \FFM::fromDisk('videos')->open($uploadedVideoPath);


        $format = new X264("libmp3lame");
        /** @var Media $videoFile */

        if($this->addWatermark){
            $filter = new CustomFilter(
                "drawtext=text='http\\://Refahshahrvand.com':
                fontfile='/Users/mhshoghi/development/flutter/bin/cache/artifacts/material_fonts/Roboto-Medium.ttf':
                fontcolor=blue :
                fontsize=24 :
                box=1 :
                boxcolor=white@0.5 :
                boxborderw=5 :
                x=10 :
                y=(h - text_h - 10)");

            $videoUploaded = $videoUploaded->addFilter($filter);
        }


        /** @var Media $videoFile */
        $videoFile = $videoUploaded->export()
            ->toDisk('videos')
            ->inFormat($format);

        $videoFile->save($this->userId . '/' .$video->slug.'.mp4');

        $video->duration = $videoUploaded->getDurationInSeconds();
        $video->state = Video::STATE_CONVERTED;
        $video->save();

        Storage::disk('videos')->delete($uploadedVideoPath);
    }
}
