<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Http\Resources\ProjectsPositionsResource;
use App\Models\Location;
use App\Models\Projects;
use App\Models\ProjectsAreas;
use App\Models\ProjectsPositions;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function location(ProjectsPositions $position){
        $location = $position->load(['area','area.file']);
        $all_positions = ProjectsPositions::where('project_id',$location->project_id)->where('area_id',$location->area_id)->pluck('id');
        $all_device = Location::whereIn('position_id',$all_positions)->get();
        $location->all_device = $all_device;
        return new ProjectsPositionsResource($location);
    }

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
