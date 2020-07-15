<?php

namespace App\Http\Controllers\Api;

//use App\Console\Commands\UpdateProStage;
use App\Jobs\UpdateProStage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectsRequest;
use App\Http\Resources\ProjectsResources;
use App\Models\Position;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request , Projects $projects)
    {
        $projects = $projects->with(['customs']);
        $request->status && $projects->where('status',$request->status);
        $request->name   && $projects->whereHas('customs',function($query) use ($request){
            $query->where('company_name','like',"%{$request->name}%")
                ->orWhere('company_addr','like','%'.$request->name.'%')
                ->orWhere('name','like',"%{$request->name}%")
                ->orWhere('number','like',"%{$request->name}%");
        });
        $projects = $projects->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);
        foreach ($projects as $k => $v){
            $v->position_count = Position::where('project_id',$v->id)->count();
            //设备数 ??
            $v->device_count = Position::where('project_id',$v->id)->whereNotNull('device_id')->count();
        }
        return response(new ProjectsResources($projects));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectsRequest $request,Projects $projects)
    {
        DB::transaction(function() use ($request, &$projects){
            $projects = $projects->create($request->all());
            $projects->areas()->createMany(json_decode($request->areas,true));
            $projects->stages()->createMany(json_decode($request->stages,true));
        });
        dispatch(new UpdateProStage(["project_id"=>$projects->id]));
        return response(new ProjectsResources($projects->load('areas')->load('stages')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Projects $project)
    {
        return new ProjectsResources($project->load('areas')->load('stages')->load('position'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Projects $project)
    {
        return new ProjectsResources($project->load('areas')->load('stages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectsRequest $request,Projects $project)
    {
        DB::transaction(function() use ($request, &$project){
            $project->update($request->all());
            $project->areas()->delete();
            $project->stages()->delete();
            $project->areas()->createMany(json_decode($request->areas,true));
            $project->stages()->createMany(json_decode($request->stages,true));
        });
        dispatch(new UpdateProStage(["project_id"=>$project->id]));
        return response(new ProjectsResources($project->load('areas')->load('stages')),201);
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

    public function positions(Projects $project){
        return new ProjectsResources($project->load('areas')->load('stages')->load('position'));
    }
}
