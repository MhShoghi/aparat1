<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    //region types
    const TYPES = [self::TYPE_ADMIN, self::TYPE_USER];
    const TYPE_ADMIN = "admin";
    const TYPE_USER = "user";
    //endregion types

    //region model configs
    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'mobile' , 'avatar' , 'type' , 'website' , 'verify_code' , 'verified_at'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'verify_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];
    //endregion model configs

    //region custom_methods

    /**
     * Find user to login into application through email or mobile
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        $user = static::where('mobile', $username)->orWhere('email',$username)->first();

        return $user;
    }


    public function isAdmin()
    {
        return $this->type === self::TYPE_ADMIN;
    }

    public function isBaseUser()
    {
        return $this->type === self::TYPE_USER;
    }
    //endregion custom_methods

    //region setters
    public function setMobileAttribute($value){
        $this->attributes['mobile'] = to_valid_mobile_number($value);
    }

    //endregion setters

    //region relations
    public function channel(){
        return $this->hasOne(Channel::class);
    }

    public function category(){
        return $this->hasMany(Category::class);
    }

    public function playlists(){
        return $this->hasMany(Playlist::class);
    }

    //endregion relations
}
