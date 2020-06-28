<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomersRequest extends FormRequest
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
        return [
            'company_name'      => 'required',
            'type'              => 'required',
            'contact'           => ['required',
            function ($attribute, $value, $fail) {
                $data = json_decode($value,true);
                if(!is_array($data)){
                    return $fail($attribute.' is invalid.');
                }
                $arr = ['contact','contact_phone','job'];
                foreach ($data as $k => $v){
                    foreach ($arr as $k1 => $v1){
                        if(!isset($v[$v1]) || empty($v[$v1])){
                            return $fail('array['.$k.'] '.$v1.' is empty.');
                            break;
                        }
                    }
                }
            }
            ]
        ];
    }
}
