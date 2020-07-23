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
        $data=parent::toArray($request);
        $data['project'] = new ProjectsResources($this->whenLoaded('project'));
        $data['type']=$data['type']==1?"数据丢失":"数据异常";
        return $data;       
        
    }
}
