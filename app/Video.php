<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Video extends Model
{
    use SoftDeletes;


    //region state constants

    const STATE_PENDING = 'pending'; // In Queue to process
    const STATE_CONVERTED = 'converted'; // Complete converted
    const STATE_ACCEPTED = 'accepted'; // Accepted
    const STATE_BLOCKED = 'blocked'; // Not allowed
    const STATES = [self::STATE_PENDING, self::STATE_CONVERTED, self::STATE_ACCEPTED, self::STATE_BLOCKED];

    //endregion state constants

    //region model configs

    protected $table = 'videos';

    protected $fillable = [
        'user_id' , 'category_id',
        'channel_category_id' ,
        'slug' , 'title' ,
        'info' , 'duration' ,
        'banner', 'enable_comments', 'publish_at', 'state'];

    //endregion model configs

    // region relations
    public function playlist(){
        return $this->belongsToMany(Playlist::class,'playlist_videos');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'video_tags');
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function viewers()
    {
        // TODO: Add information for users who have not logged in yet
        return $this->belongsToMany(User::class, 'video_views')->withTimeStamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //endregion relations

    //region model methods override
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function toArray()
    {
     $data = parent::toArray();
     $conditions = [
         'video_id' => $this->id,
         'user_id' => auth('api')->check() ? auth('api')->id() : null,
     ];
     if(!auth('api')->check()){
         $conditions['user_ip'] = client_ip();
     }

     $data['liked'] = VideoFavourites::where($conditions)->count();

     return $data;
   }
    //endregion model methods override

    //region custom methods
    public function isInState($state){
        return $this->state === $state;
    }
    public function isPending()
    {
        return $this->isInState(self::STATE_PENDING);
    }

    public function isAccepted()
    {
        return $this->isInState(self::STATE_ACCEPTED);
    }

    public function isConverted()
    {
        return $this->isInState(self::STATE_CONVERTED);
    }

    public function isblocked()
    {
        return $this->isInState(self::STATE_BLOCKED);
    }

    //endregion custom methods

    //region custom static methods
    public static function whereNotRepublished(){
        return static::whereRaw('id not in(select video_id from video_republishes)');
    }

    public static function whereRepublished(){
        return static::whereRaw('id in (select video_id from video_republishes)');
    }

    /**
     * @param $userId
     * @return Builder
     */
    public static function views($userId)
    {
        return static::where('videos.user_id', $userId)
            ->join('video_views', 'videos.id', '=' , 'video_views.video_id');
    }

    public static function channelComments($userId)
    {
        return static::where('videos.user_id', $userId)
            ->join('comments', 'videos.id' , '=' , 'comments.video_id');
    }

    //endregion custom static methods
}
