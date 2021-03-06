<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    //region model configs
    protected $table = 'tags';

    protected $fillable = ['title'];
    //endregion model configs

    //region relations
    public function videos(){
        return $this->belongsToMany(Video::class,'video_tags');
    }
    //endregion relations

    //region override model methods
    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title
        ];
    }
    //endregion toArray
}
