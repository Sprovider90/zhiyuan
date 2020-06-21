<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data=parent::toArray($request);
        $data['customer'] = new CustomersResources($this->whenLoaded('customer'));
        $data['status']=$data['status']==1?"正常":"禁用";
        $data['type']=$data['type']==1?"数据中心":"客户平台";
        return $data;       
        
    }
}
