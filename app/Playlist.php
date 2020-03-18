<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $table = 'playlists';

    protected $fillable = ['user_id' , 'title'];


    public function videos()
    {
        return $this->belongsToMany(Video::class,'playlist_videos');
    }

}
