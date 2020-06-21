<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    public function rules()
    {
    	switch($this->method())
        {
            // CREATE
            case 'POST':
            {
            	$rules=[
		            'name' => 'required|between:6,20|regex:/^[A-Za-z0-9]+$/|unique:users,name',
		            'phone' => 'required|string|unique:users,phone',
		            'truename' => 'required|string',
		            'password' => 'required|alpha_dash|between:6,20',
		            'type' => 'required|numeric',
		            'customer_id' => 'required|numeric',
		            'roles' => 'required|string'
		        ];
		        if($this->type==1){
		        	$rules=[
			            'name' => 'required|between:6,20|regex:/^[A-Za-z0-9]+$/|unique:users,name',
			            'phone' => 'required|string|unique:users,phone',
			            'truename' => 'required|string',
			            'password' => 'required|alpha_dash|between:6,20',
			            'type' => 'required|numeric',
			            'roles' => 'required|string'            
			        ];
		        }
		    }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            case 'GET':
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