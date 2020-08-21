<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ThresholdsRequest extends FormRequest
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
                    'name' => 'required|string|unique:thresholds,name',
                ];
                break;
            }
            case 'PATCH':{
                $rules=[
                    'thresholdinfo' => 'string',
                    'status' => 'numeric'
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
