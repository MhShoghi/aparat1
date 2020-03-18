<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['title'];


    public function videos(){
        return $this->belongsToMany(Video::class,'video_tags');
    }
}
