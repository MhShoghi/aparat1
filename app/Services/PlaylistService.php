<?php


namespace App\Services;


use App\Http\Requests\Playlist\GetMyPlaylistRequest;
use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Playlist;

class PlaylistService extends BaseService
{

    public static function getAllPlaylist(ListPlaylistRequest $request)
    {
        return Playlist::all();
    }


    public static function getMyPlaylist(GetMyPlaylistRequest $request)
    {
        return Playlist::where('user_id', auth()->id())->get();
    }
}
