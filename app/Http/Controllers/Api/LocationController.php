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

    public function location(Projects $project,ProjectsAreas $area){
        $all_positions = ProjectsPositions::where('project_id',$project->id)->where('area_id',$area->id)->pluck('id');
        $area = $area->load('file');
        $all_device = Location::whereIn('position_id',$all_positions)->get();
        $data = array(
            'area'          => $area,
            'all_device'    => $all_device,
        );
        return new ProjectsPositionsResource($data);
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
