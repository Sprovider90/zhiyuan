<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectsPositionsRequest;
use App\Http\Resources\ProjectsPositionsResources;
use App\Models\ProjectsPositions;

class ProjectsPositionsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectsPositionsRequest $request,ProjectsPositions $projectsPositions)
    {
        $projectsPositions = $projectsPositions->create($request->all());
        return response(new ProjectsPositionsResources($projectsPositions));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectsPositionsRequest $request,ProjectsPositions $projectsPosition)
    {
        $projectsPosition->update($request->all());
        return response(new ProjectsPositionResources($projectsPosition));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
