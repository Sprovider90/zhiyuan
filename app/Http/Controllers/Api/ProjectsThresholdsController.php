<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectsThresholdsRequest;
use App\Models\Projects;
use App\Models\ProjectsThresholds;
use App\Http\Resources\ProjectsThresholdsResource;
class ProjectsThresholdsController extends Controller
{
    public function store(ProjectsThresholdsRequest $request, ProjectsThresholds $ProjectsThresholds,Projects $project)
    {
        $ProjectsThresholds->fill($request->all());
        $ProjectsThresholds->project_id=$project->id;
        $ProjectsThresholds->save();

        return new ProjectsThresholdsResource($ProjectsThresholds);
    }

    public function update(Projects $project,ProjectsThresholds $projectsthreshold)
    {
       // echo $project->id;exit;
        echo $projectsthreshold->id;exit;
//        $Thresholds = Thresholds::findOrFail($id);
//        $attributes = $request->only(['thresholdinfo',"status"]);
//        try{
//            $Thresholds->update($attributes);
//        }catch(\Exception $e){
//            abort(400, '内部错误');
//        }

        return new ThresholdsResource($Thresholds);
    }
}
