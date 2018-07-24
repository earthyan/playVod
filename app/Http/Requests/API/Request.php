<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 11:29
 */

namespace App\Http\Requests\API;

use App\Http\Requests\Request as BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

abstract class Request extends BaseRequest
{
    /**
     * API 接口验证返回JSON数据
     * @param Validator $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        $response = response()->json(['code' => 1, 'msg' => $validator->errors()->first()]);
        throw new ValidationException($validator, $response);
    }
}