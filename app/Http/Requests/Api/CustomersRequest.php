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
                $arr = ['contact','contact_phone','job'];
                $arr1 = ['联系人','联系电话','职位'];
                foreach ($data as $k => $v){
                    $index = 0 ;
                    foreach ($arr as $k1 => $v1){
                        if(!isset($v[$v1]) || empty($v[$v1])){
                            return $fail($arr1[$index].' 不能为空');
                            $index++;
                            break;
                        }
                    }
                }
            }
            ]
        ];
    }
}
