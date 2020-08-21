<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectsWaringSettingRequest;
use App\Http\Resources\ProjectsWaringSettingResource;
use App\Models\Projects;
use App\Models\ProjectsWaringSetting;
use Illuminate\Http\Request;

class ProjectsWaringSettingController extends Controller
{
    public function store(ProjectsWaringSettingRequest $request, ProjectsWaringSetting $waringsetting,Projects $project)
    {
        $waringsetting->fill($request->all());
        $waringsetting->project_id=$project->id;
        $waringsetting->save();

        return new ProjectsWaringSettingResource($waringsetting);
    }

    public function update(Projects $project,ProjectsWaringSetting $waringsetting,ProjectsWaringSettingRequest $request)
    {

        $waringsetting->update($request->all());
        return new ProjectsWaringSettingResource($waringsetting);
    }
}
