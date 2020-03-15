<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = "channels";

    protected $fillable = ['user_id', 'name' , 'info' , 'banner' , 'socials'];


    public function user(){
        return $this->belongsTo(User::class);
    }


    public function setSocialsAttribute($value){
        $value = is_array($value) ? json_encode($value) : $value;
        $this->attributes['socials'] = $value;
    }

    public function getSocialsAttribute(){
        return json_decode($this->attributes['socials'],true);
    }
}
