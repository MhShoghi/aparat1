<?php

namespace App\Http\Requests;

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
            "video_id" => 'required', // TODO: videoId validation
            "title" => 'required|string|max:255',
            "category" => 'required|exists:categories,id',
            "info" => 'nullable|string',
            "tags" => 'nullable|array',
            "tags.*" => 'exists:tags,id',
            "playlist" => 'nullable|exists:playlist,id',
            "channel_category" => 'nullable|string', //TODO: Channel Category
            "banner" => 'nullable|string', //TODO: Banner should be uploaded before create video
            "publish_at" => 'nullable|date'
        ];
    }
}
