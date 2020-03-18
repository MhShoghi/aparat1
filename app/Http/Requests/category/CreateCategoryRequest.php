<?php

namespace App\Http\Requests\Category;

use App\Rules\UploadedCategoryBannerId;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            "title" => "string|min:2|max:100",
            "icon" => "nullable|string", //Todo: what package can we use for icon?
            "banner_id" => ["nullable",new UploadedCategoryBannerId()]
        ];
    }
}
