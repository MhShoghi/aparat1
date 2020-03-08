<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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
}
