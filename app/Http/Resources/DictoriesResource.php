<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DictoriesResource extends JsonResource
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

        $data["value"]=json_decode($data["value"]);
        //$data["valuelook"]=implode(",",array_column(json_decode($data["value"]),"name"));
        return $data;

    }
}
