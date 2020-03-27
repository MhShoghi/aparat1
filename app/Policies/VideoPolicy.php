<?php

namespace App\Policies;

use App\User;
use App\Video;
use App\VideoFavourites;
use App\VideoRepublish;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function changeState(User $user, Video $video = null)
    {
        return $user->isAdmin();
    }

    public function republish(User $user, Video $video = null)
    {
        return $video && $video->isAccepted() &&  (
            $video->user_id != $user->id &&
            VideoRepublish::where([
                'user_id'=>$user->id ,
                'video_id' => $video->id])->count() < 1
            );
    }

    public function likeVideo(User $user = null, Video $video = null)
    {


        if($video && $video->isAccepted()){

            $conditions = [
                'video_id' => $video->id,
                'user_id' => $user ? $user->id : null,
                'user_ip' => client_ip()
            ];

            return VideoFavourites::where($conditions)->count() == 0;
        }

        return false;
    }

    public function dislikeVideo(User $user = null, Video $video = null)
    {
        if($video && $video->isAccepted()){
            $conditions = [
                'video_id' => $video->id,
                'user_id' => $user ? $user->id : null,
                'user_ip' => client_ip()
            ];

            return VideoFavourites::where($conditions)->count() !== 0;
        }
    }
}
