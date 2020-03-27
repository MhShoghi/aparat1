<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens, SoftDeletes;

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

    public function follow(User $user)
    {
        return UserFollowing::create([
            'user_id1' => $this->id,
            'user_id2' => $user->id
        ]);
    }

    public function unfollow(User $user)
    {
        return UserFollowing::where([
            'user_id1' => $this->id,
            'user_id2' => $user->id
        ])->delete();
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

    public function favouriteVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoFavourites::class,
            'user_id', // video_favourites.user_id
            'id', //video.id
            'id', //user.id
            'video_id'); // video_favourites.video_id
    }

    public function channelVideos()
    {
        return $this->hasMany(Video::class)->selectRaw('*,0 as republished');
    }

    public function republishVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoRepublish::class,
            'user_id', //republished_video.user_id
            'id', //video.id
            'id', //user.id
            'video_id')->selectRaw('videos.*,1 as republished'); //republished_video.video_id
    }

    public function videos()
    {
        return $this->channelVideos()
            ->union($this->republishVideos());
    }

    public function followings(){ // Those who follow me
        return $this->hasManyThrough(
            User::class,
            UserFollowing::class,
            'user_id1',
            'id',
            'id',
            'user_id2');
    }

    public function followers()
    {  // Those I Got follow
        return $this->hasManyThrough(
            User::class,
            UserFollowing::class,
            'user_id2',
            'id',
            'id',
            'user_id1'
        );
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }



    public function views()
    {
        return $this->belongsToMany(
            Video::class,
            'video_views')->withTimeStamps();
    }

    //endregion relations
}
