<?php

namespace App\Policies;

use App\Channel;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function follow(User $user,User $otherUser)
    {
        return ($user->id != $otherUser->id) &&
            (!$user->followings()->where('user_id2',$otherUser->id)->count()) &&
            ($otherUser->type != User::TYPE_ADMIN);
    }

    public function unfollow(User $user, User $otherUser)
    {
        return ($user->id != $otherUser->id) &&
            ($user->followings()->where('user_id2',$otherUser->id)->count()) &&
            ($otherUser->type != User::TYPE_ADMIN);
    }

    public function seeFollowingList(User $user)
    {
        return true;
    }

    public function seeFollowerList(User $user)
    {
        return true;
    }
}
