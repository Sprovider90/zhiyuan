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
                    return $fail('联系人数据无效');
                }
                $arr = ['contact','contact_phone'];
                $arr1 = ['联系人','联系人电话'];
                foreach ($data as $k => $v){
                    foreach ($arr as $k1 => $v1){
                        if(!isset($v[$v1]) || empty($v[$v1])){
                            return $fail('第'.($k+1).'行 '.$arr1[$k1].'不能为空');
                            break;
                        }
                    }
                }
            }
            ]
        ];
    }
}
