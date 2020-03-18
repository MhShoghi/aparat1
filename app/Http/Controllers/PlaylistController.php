<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\GetMyPlaylistRequest;
use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Services\PlaylistService;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function getAll(ListPlaylistRequest $request){

        return PlaylistService::getAllPlaylist($request);
    }

    public function getMy(GetMyPlaylistRequest $request)
    {
        return PlaylistService::getMyPlaylist($request);
    }

    public function create(CreatePlaylistRequest $request)
    {
        return PlaylistService::createPlaylist($request);
    }
}
