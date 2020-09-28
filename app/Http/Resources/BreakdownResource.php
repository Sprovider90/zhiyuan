<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BreakdownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $hash=[1=>"数据丢失",2=>"数据异常",3=>"设备离线",4=>"设备上线"];
        $data=parent::toArray($request);
        $data['project'] = new ProjectsResources($this->whenLoaded('project'));
        $data['devices'] = new DeviceResource($this->whenLoaded('devices'));

        $data['type']=$hash[$data['type']];
        return $data;

    }
}
