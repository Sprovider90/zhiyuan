<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WarnigsSmsRequest extends FormRequest
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
                    'content' => 'required|string',
                    //'pics' => 'required|string',
                ];
                break;
            }
            case 'PATCH':{
                $rules=[
                    'content' => 'string',
                    'pics' => 'string'
                ];
                break;
            }
            // UPDATE
            case 'PUT':
            case 'GET': {
                $rules=[
                    'warnig_id' => 'required'
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
