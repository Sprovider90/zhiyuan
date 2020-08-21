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

        return $data;
    }
}
