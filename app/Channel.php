<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{

    use SoftDeletes;
    //region model configs
    protected $table = "channels";

    protected $fillable = ['user_id', 'name' , 'info' , 'banner' , 'socials'];

    //endregion model configs

    //region relations
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->user->videos();
    }
    //endregion relations


    public function getRouteKeyName()
    {
        return 'name';
    }
    //region setters
    public function setSocialsAttribute($value){
        $value = is_array($value) ? json_encode($value) : $value;
        $this->attributes['socials'] = $value;
    }
    //endregion setters

    //region getters
    public function getSocialsAttribute(){
        return json_decode($this->attributes['socials'],true);
    }
    //endregion getters
}
