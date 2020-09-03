<?php

namespace App\Http\Requests\Api;

use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;

class PositionsRequest extends FormRequest
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
        $id = $this->route('poistion') ?  $this->route('poistion')->id : 0;
        var_dump($this->route('postion'));
        var_dump($id);exit;
        return [
            'project_id'    => 'required',
            'number'        => 'required',
            'name'          => 'required',
            'area_id'       => 'required',
            'device_id'     => ['required',
                function ($attribute, $value, $fail) use($id) {
                    $flg = Position::where('id','<>',$id)->where('device_id',$value)->where('status',1)->first();
                    if($flg){
                        return $fail('设备已被绑定');
                    }
                }
            ]
        ];
    }
}
