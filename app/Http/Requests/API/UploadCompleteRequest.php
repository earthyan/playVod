<?php

namespace App\Http\Requests\API;

/**
 * Class UploadCompleteRequest
 * @package App\Http\Requests\API
 * @property $token
 * @property $totalPartNumber
 * @property $uploadId
 */
class UploadCompleteRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token' => 'required',
            'totalPartNumber' => 'required|integer|min:1',
            'uploadId' => 'required'
        ];
    }
}