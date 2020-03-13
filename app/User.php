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

    const TYPES = [self::TYPE_ADMIN, self::TYPE_USER];
    const TYPE_ADMIN = "admin";
    const TYPE_USER = "user";


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


    public function setMobileAttribute($value){
        $this->attributes['mobile'] = to_valid_mobile_number($value);
    }

    public function channel(){
        return $this->hasOne(Channel::class);
    }
}
