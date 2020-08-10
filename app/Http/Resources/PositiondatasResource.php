<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositiondatasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //$data=parent::toArray($request);
        $data=json_decode($request,true);
//        $data['project'] = new ProjectsResources($this->whenLoaded('project'));
//        $data['type']=$data['type']==1?"数据丢失":"数据异常";
        return $data;       
        
    }
}
