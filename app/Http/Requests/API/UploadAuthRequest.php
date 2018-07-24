<?php

namespace App\Http\Requests\API;

/**
 * Class UploadAuthRequest
 * @package App\Http\Requests\API
 * @property $title
 * @property $filename
 * @property $fileSize
 */
class UploadAuthRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'filename' => 'required',
            'fileSize' => 'required'
        ];
    }
}