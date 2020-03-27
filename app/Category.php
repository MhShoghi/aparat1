<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{

    use SoftDeletes;
    //region model configs
    protected $table = 'categories';

    protected $fillable = ['title' , 'user_id' , 'icon' , 'banner'];
    //endregion model configs

    //region relations
    public function user(){
        return $this->belongsTo(User::class);
    }
    //endregion relations
}
