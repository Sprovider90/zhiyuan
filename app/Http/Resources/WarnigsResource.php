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
        $zhibaos=["humidity"=>"湿度","temperature"=>"温度","formaldehyde"=>"甲醛","PM25"=>"PM25","CO2"=>"CO2","TVOC"=>"TVOC"];
        if(isset($data["threshold_keys"])&&!empty($data["threshold_keys"])){
            $arr=explode(",",$data["threshold_keys"]);
            foreach ($arr as $k=>&$v){
                $v=$zhibaos[$v];
            }
            $data['threshold_keys'] = implode(',',$arr);
        }


        return $data;
    }
}
