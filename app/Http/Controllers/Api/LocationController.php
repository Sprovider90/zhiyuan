<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\ProjectsAreas;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function store(LocationRequest $request , Location $location){
        $data = $request->all();
        $location = $location->create($data);
        return response(new LocationResource($location),201);
    }


    public function update(LocationRequest $request , Location $location){
        $location->update($request->all());
        return response(new LocationResource($location),201);
    }


    public function destroy($id)
    {
        Location::where('id',$id)->delete();
        return response(null, 204);
    }
}
