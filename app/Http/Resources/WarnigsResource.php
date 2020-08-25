<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarnigsResource extends JsonResource
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
//        /$data['project'] = ProjectsResources::make($this->whenLoaded('project'));

        return $data;
    }
}
