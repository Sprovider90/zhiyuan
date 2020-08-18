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

}
