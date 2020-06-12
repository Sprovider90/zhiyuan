<?php

namespace App\Http\Requests\Api;

class RoleRequest extends FormRequest
{
    public function rules()
    {
    	switch($this->method())
        {
            case 'POST':
	            return [
	            'name'=>'required|unique:roles|max:10',
	            'permissions' =>'required',
	        	];
            case 'PUT':
            case 'PATCH':
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            };
        }

        
    }

}