<?php


namespace App\Services;


use App\Comment;
use App\Http\Requests\Comment\ChangeCommentStateRequest;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\GetAllCommentsRequest;
use App\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentService extends BaseService
{

    public static function getAllComments(GetAllCommentsRequest $request)
    {


        $comments = Comment::channelComments($request->user()->id);


            if($request->has('state')){
                $comments = $comments->where('comments.state', $request->state);
            }

        return $comments->get();
    }

    public static function createComment(CreateCommentRequest $request)
    {
        $video = Video::find($request->video_id);
        $user = $request->user();
        $comment = $request->user()->comments()->create([
           'video_id' => $request->video_id,
           'parent_id' => $request->parent_id,
           'body' => $request->body,
           'state' => $video->user_id == $user->id
                ? Comment::STATE_ACCEPTED
                : Comment::STATE_PENDING
        ]);


        return $comment;
    }

    public static function changeState(ChangeCommentStateRequest $request)
    {
        $comment = $request->comment;
        $comment->state = $request->state;
        $comment->save();


       return response([
           'message' => 'State changed successfully!'
       ],200);
    }

    public static function deleteComment(DeleteCommentRequest $request)
    {
        try {
          DB::beginTransaction();
        $request->comment->delete();
          DB::commit();

            return response([
                'message' => 'Comment deleted successfully!'
            ],200);

        }catch (\Exception $exception){
            Log::error($exception);
            DB::rollBack();
            return response([
                'Error!!!'
            ],500);
        }
    }
}
