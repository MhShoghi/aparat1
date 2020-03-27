<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use SoftDeletes;
    const COMMENT_STATES = [self::STATE_PENDING,self::STATE_ACCEPTED,self::STATE_READ];
    const STATE_ACCEPTED = 'accepted';
    const STATE_PENDING = 'pending';
    const STATE_READ = 'read';

    protected $table = "comments";
    protected $fillable = ["user_id", "video_id", "parent_id", "body", "state"];


    //region relations

    public function video()
    {
        $this->belongsTo(Video::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function parent(){
        return $this->belongsTo(Comment::class,'parent_id' );
    }

    public function children()
    {
        return $this->hasMany(Comment::class,'parent_id');
    }
    //endregion


    //region custom static methods

    public static function channelComments($userId)
    {
        return Comment::join('videos', 'comments.video_id' , '=' , 'videos.id')
            ->where('videos.user_id',$userId)->selectRaw('comments.*');
    }

    //endregion

    //region override model methods
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($comment){
            $comment->children()->delete();
        });
    }
    //endregion
}
