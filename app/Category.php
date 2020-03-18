<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['title' , 'user_id' , 'icon' , 'banner'];


    //region relations
    public function user(){
        return $this->belongsTo(User::class);
    }
    //endregion relations
}
