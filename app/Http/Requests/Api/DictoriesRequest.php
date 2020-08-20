<?php

namespace App\Http\Requests\Api;

class DictoriesRequest extends FormRequest
{
    public function rules()
    {
    	switch($this->method())
        {
            // CREATE
            case 'POST':
            case 'PATCH':{
            $rules=[
                'value' => 'required',

            ];
            break;
            }
            // UPDATE
            case 'PUT':
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            };
        }

        return $rules;
    }

}
