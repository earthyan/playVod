<?php

namespace App\Http\Requests\API;

/**
 * Class InitMultiRequest
 * @package App\Http\Requests\API
 * @property $token
 */
class InitMultiRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token' => 'required',
        ];
    }
}