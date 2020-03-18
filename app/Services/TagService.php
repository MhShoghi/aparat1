<?php


namespace App\Services;


use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\GetAllTagsRequest;
use App\Tag;

class TagService extends BaseService
{

    public static function getAllTags(GetAllTagsRequest $request)
    {
        $tags =  Tag::all();

        return $tags;
    }

    public static function createTag(CreateTagRequest $request)
    {
        $data = $request->validated();

        $tag =  Tag::create($data);

        return $tag;
    }


}
