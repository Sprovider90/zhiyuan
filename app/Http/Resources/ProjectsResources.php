<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsResources extends JsonResource
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
        $data['customs'] = new CustomersResources($this->whenLoaded('customs'));
        $data['thresholds'] = new ProjectsThresholdsResource($this->whenLoaded('thresholds'));
        $data['waringsetting'] = new ProjectsWaringSettingResource($this->whenLoaded('waringsetting'));
        $data['position'] = new PositionsResource($this->whenLoaded('position'));
        $data['position.device'] = new DeviceResource($this->whenLoaded('position.device'));

        return $data;
    }
}
