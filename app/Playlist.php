<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    //region model configs
    protected $table = 'playlists';

    protected $fillable = ['user_id' , 'title'];

    //endregion model configs

    //region relations
    public function videos()
    {
        return $this->belongsToMany(Video::class,'playlist_videos');
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    //endregion relations

}
