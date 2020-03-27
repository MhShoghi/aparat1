<?php

namespace App\Http\Requests\Video;

use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

class RepublishVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return \Illuminate\Support\Facades\Gate::allows("republish",$this->video);
        //TODO: who can republishes video?
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
