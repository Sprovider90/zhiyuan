<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProjectsWaringSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':{
                $rules=[
                    'remind_time' => 'required|numeric',
                    'percentage' => 'required|string',
                    'notice_start_time' => 'required|string',
                    'notice_end_time' => 'required|string',
                    'notice_phone' => 'required|string'
                ];
                break;
            }
            case 'PATCH':{
                $rules=[
                    'remind_time' => 'numeric',
                    'percentage' => 'string',
                    'notice_start_time' => 'string',
                    'notice_end_time' => 'string',
                    'notice_phone' => 'string'
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
            }
        }

        return $rules;
    }
}
