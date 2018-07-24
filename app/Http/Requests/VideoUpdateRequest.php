<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class VideoUpdateRequest extends Request
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
            'title'=>'required|max:255',
            'youku_id'=>'required|max:255',
            'aliyun_id'=>'required|max:255',
            'level'=>'required',
            'current_type'=>'required',
        ];
    }
}
