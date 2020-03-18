<?php


namespace App\Services;


use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\GetMyPlaylistRequest;
use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Playlist;

class PlaylistService extends BaseService
{

    public static function getAllPlaylist(ListPlaylistRequest $request)
    {
        return Playlist::all('id','title');
    }


    public static function getMyPlaylist(GetMyPlaylistRequest $request)
    {
        return Playlist::where('user_id', auth()->id())->get();
    }

    public static function createPlaylist(CreatePlaylistRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $playlist = $user->playlists()->create($data);
        return response($playlist,200);


    }
}
