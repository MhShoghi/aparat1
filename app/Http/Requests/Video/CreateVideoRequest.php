<?php

namespace App\Http\Requests\Video;

use App\Rules\UploadedVideoBannerId;
use App\Rules\UploadedVideoId;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "video_id" => ['required',new UploadedVideoId()],
            "title" => 'required|string|max:255',
            "category" => 'required|exists:categories,id',
            "info" => 'nullable|string',
            "tags" => 'nullable|array',
            "tags.*" => 'exists:tags,id',
            "playlist" => 'nullable|exists:playlist,id', //TODO: select user own playlist
            "channel_category" => 'nullable|string', //TODO: Channel Category
            "banner" => ['nullable','string',new UploadedVideoBannerId()], //TODO: Banner should be uploaded before create video
            "publish_at" => 'nullable|date'
        ];
    }
}
