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
//        if(isset($data["threshold_keys"])&&!empty($data["threshold_keys"])){
//            $arr=explode(",",$data["threshold_keys"]);
//            foreach ($arr as $k=>$v){
//
//            }
//        }
//        $data['threshold_keys'] = ProjectsResources::make($this->whenLoaded('project'));

        return $data;
    }
}
