<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectsRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $id = $this->route('project') ? $this->route('project')->id : 0;
        return [
            'name'              => 'required|unique:App\Models\Projects,name,'.$id,
            'customer_id'       => 'required',
            'hcho'              => 'required',
            'tvoc'              => 'required',
            'stages'            => ['required',
                function ($attribute, $value, $fail) {
                    $data = json_decode($value,true);
                    if(!is_array($data)){
                        return $fail($attribute.' is invalid.');
                    }
                    $arr = ['stage_name','start_date','end_date','threshold_id','stage','default'];
                    foreach ($data as $k => $v){
                        foreach ($arr as $k1 => $v1){
                            if(!isset($v[$v1]) || strlen($v[$v1]) == 0){
                                return $fail('array['.$k.'] '.$v1.' is empty.');
                                break;
                            }
                        }
                    }
                }
            ],
            'areas'             => ['required',
                function ($attribute, $value, $fail) {
                    $data = json_decode($value,true);
                    if(!is_array($data)){
                        return $fail($attribute.' is invalid.');
                    }
                    $arr = ['area_name'];
                    foreach ($data as $k => $v){
                        foreach ($arr as $k1 => $v1){
                            if(!isset($v[$v1]) || empty($v[$v1])){
                                return $fail('array['.$k.'] '.$v1.' is empty.');
                                break;
                            }
                        }
                    }
                }
            ],
        ];
    }
}
