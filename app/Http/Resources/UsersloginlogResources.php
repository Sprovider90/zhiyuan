<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersloginlogResources extends JsonResource
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
        $data['users'] = new UserResource($this->whenLoaded('users'));
     
        return $data; 
    }
}
