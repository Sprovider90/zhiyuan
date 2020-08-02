<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectsAreasResource;
use App\Models\ProjectsAreas;
use Illuminate\Http\Request;

class ProjectAreaController extends Controller
{
    //

    public function location(ProjectsAreas $area){
        $area->load(['file','locations']);
        return new ProjectsAreasResource($area);
    }
}
