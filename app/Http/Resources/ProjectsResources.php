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
        $data['position'] = new CustomersResources($this->whenLoaded('position'));
        $data['position.device'] = new CustomersResources($this->whenLoaded('position.device'));
        $data['thresholds'] = new ProjectsThresholdsResource($this->whenLoaded('thresholds'));
        $data['waringsetting'] = new ProjectsWaringSettingResource($this->whenLoaded('waringsetting'));

        return $data;
    }
}
