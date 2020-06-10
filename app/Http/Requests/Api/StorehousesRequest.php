<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class StorehousesRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required',
            Rule::unique('storehouses')->ignore(request()->route('storehouse'))],
        ];
    }

}