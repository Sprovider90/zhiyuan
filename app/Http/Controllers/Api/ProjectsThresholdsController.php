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

    public function update(Projects $project,ProjectsThresholds $projectsthreshold,ProjectsThresholdsRequest $request)
    {

        $projectsthreshold->update($request->all());
        return new ProjectsThresholdsResource($projectsthreshold);
    }
}
