<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProjectsThresholdsRequest extends FormRequest
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
                    //'project_id' => 'required|numeric',
                    'stage_id' => 'required|numeric',
                    'thresholdinfo' => 'required|string',

                ];
                break;
            }
            case 'PATCH':{
                $rules=[
                    'project_id' => 'numeric',
                    'stage_id' => 'numeric',
                    'thresholdinfo' => 'string',
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
