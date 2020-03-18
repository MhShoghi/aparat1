<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

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
