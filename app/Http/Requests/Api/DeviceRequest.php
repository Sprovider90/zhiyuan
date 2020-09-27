<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
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
        $id = $this->route('device') ?  $this->route('device')->id : 0;
        return [
            //
            'device_number' => 'required|string|unique:devices,device_number,'.$id,
            'come_date'     => 'required|date',
            'model'         => 'required|numeric',
            'manufacturer'  => 'required|string',
            'storehouse_id' => 'required|numeric',
            'check_data'    => 'required',
        ];
    }
    public function messages()
    {
        return [
            'device_number.unique' => '设备ID已存在'
        ];
    }
}
