<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{

    use SoftDeletes;
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

    public function toArray()
    {
        $data = parent::toArray();
        $data['count'] = $this->videos()->count();
        return $data;
    }

}
