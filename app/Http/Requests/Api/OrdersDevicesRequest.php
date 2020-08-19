<?php

namespace App\Http\Requests\Api;

use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;

class OrdersDevicesRequest extends FormRequest
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
            //
            'express_name'      => 'required',
            'express_number'    => 'required',
            'data'              => ['required',
                function ($attribute, $value, $fail) {
                    $data = json_decode($value,true);
                    if(!is_array($data)){
                        return $fail($attribute.' is invalid.');
                    }
                    $arr = ['number'];
                    foreach ($data as $k => $v){
                        foreach ($arr as $k1 => $v1){
                            if(!isset($v[$v1]) || empty($v[$v1])){
                                return $fail('array['.$k.'] '.$v1.' is empty.');
                                break;
                            }
                            if($v1 == 'number'){
                                $count = Device::where('device_number',$v[$v1])->where('status',1)->whereNotNull('customer_id')->count();
                                if(!$count){
                                    return $fail('array['.$k.'] '.$v1.' is error.');
                                    break;
                                }
                            }
                        }
                    }
                }
            ]

        ];
    }
}
