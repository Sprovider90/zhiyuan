<?php

namespace App\Http\Requests\Api;

class UsercheckRequest extends FormRequest
{
    public function rules()
    {
    	switch($this->method())
        {
            // CREATE
            case 'POST':
            case 'PATCH':
            // UPDATE
            case 'PUT':
            case 'GET':{
                $rules=[
                    'name' => 'required|between:6,20|regex:/^[A-Za-z0-9]+$/|unique:users,name',

                ];
                break;
            }
            case 'DELETE':
            default:
            {
                return [];
            };
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'name.unique' => '该用户名已被占用',
            'name.regex' => '用户名为字母或字母和数字组合',
            'name.between' => '用户名必须介于 6 - 20 个字符之间',
            'name.required' => '用户名不能为空',
        ];
    }
}
