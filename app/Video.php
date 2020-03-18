<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = [
        'user_id' , 'category_id',
        'channel_category_id' ,
        'slug' , 'title' ,
        'info' , 'duration' ,
        'banner' , 'publish_at'];


    // region relations
    public function playlist(){
        return $this->belongsToMany(Playlist::class,'playlist_videos');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'video_tags');
    }

    //endregion relations

}
