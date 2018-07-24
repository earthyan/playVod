<?php

namespace App\Http\Requests\API;

/**
 * Class UploadPartRequest
 * @package App\Http\Requests\API
 * @property $uploadId ali upload id
 * @property $partNumber part num
 * @property $checkMd5 file md5
 * @property $token
 */
class UploadPartRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'uploadId' => 'required',
            'partNumber' => 'required|integer',
            'token' => 'required'
        ];
    }
}