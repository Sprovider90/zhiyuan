<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
        $id = $this->route('location') ? $this->route('location')->id : 0;
        return [
            'position_id'  => 'required|unique:locations,position_id,'.$id,
            'left'         => 'required',
            'top'          => 'required',
        ];
    }
}
