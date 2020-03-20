<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{


    //region state constants

    const STATE_PENDING = 'pending'; // In Queue to process
    const STATE_CONVERTED = 'converted'; // Complete converted
    const STATE_ACCEPTED = 'accepted'; // Accepted
    const STATE_BLOCKED = 'blocked'; // Not allowed
    const STATES = [self::STATE_PENDING, self::STATE_CONVERTED, self::STATE_ACCEPTED, self::STATE_BLOCKED];

    //endregion state constants

    //region model configs

    protected $table = 'videos';

    protected $fillable = [
        'user_id' , 'category_id',
        'channel_category_id' ,
        'slug' , 'title' ,
        'info' , 'duration' ,
        'banner', 'enable_comments', 'publish_at', 'state'];

    //endregion model configs

    // region relations
    public function playlist(){
        return $this->belongsToMany(Playlist::class,'playlist_videos');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'video_tags');
    }

    //endregion relations

    //region model methods override
    public function getRouteKeyName()
    {
        return 'slug';
    }
    //endregion model methods override
}
