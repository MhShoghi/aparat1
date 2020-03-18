<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\GetAllTagsRequest;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getAll(GetAllTagsRequest $request){
        return TagService::getAllTags($request);
    }


    public function create(CreateTagRequest $request)
    {
        return TagService::createTag($request);
    }
}
