<?php

namespace App\Http\Controllers\Api;

//use App\Console\Commands\UpdateProStage;
use App\Http\Resources\PorjectsAreasResource;
use App\Jobs\UpdateProStage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectsRequest;
use App\Http\Resources\ProjectsResources;
use App\Models\Loginlog;
use App\Models\Position;
use App\Models\Projects;
use App\Models\ProjectsAreas;
use App\Models\ProjectsPositions;
use App\Models\ProjectsStages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\ProjectsThresholds;

class ProjectsController extends Controller
{
    /**
     * 统计状态
     * $type  1本周 2本月 3今年 默认2
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Projects $projects,Request $request){
        $date = $this->returnDate($request->type ?? 2);
        $where = [];
        $request->user()->customer_id && $where[] = ['customer_id',$request->user()->customer_id];
        //进行中 1暂停中 4施工中 5交付中 6维护中
        $ongoing_count   = Projects::where($where)->whereBetween('created_at',$date)->whereIN('status',[1,4,5,6])->count();
        //已结束 2已结束
        $over_count  = Projects::where($where)->whereBetween('created_at',$date)->where('status',2)->count();
        //未开始 0未开始
        $not_started_count   = Projects::where($where)->whereBetween('created_at',$date)->where('status',0)->count();
        //项目总数量
        $all_count  = Projects::where($where)->whereBetween('created_at',$date)->count();
        return response()->json([
            'not_started_count'     => $not_started_count,
            'over_count'            => $over_count,
            'ongoing_count'         => $ongoing_count,
            'all_count'             => $all_count,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request , Projects $projects)
    {

        $projects_query=Projects::class;
        $request->status && $projects_query=$projects->where('status',$request->status);
        $request->name   && $projects_query=$projects->whereHas('customs',function($query) use ($request){
            $query->where('company_name','like',"%{$request->name}%")
                ->orWhere('company_addr','like','%'.$request->name.'%')
                ->orWhere('name','like',"%{$request->name}%")
                ->orWhere('number','like',"%{$request->name}%");
        });
        $request->user()->customer_id && $projects_query = $projects->where('customer_id',$request->user()->customer_id);
        $projects = QueryBuilder::for($projects_query)
            ->allowedIncludes('customs','thresholds','waringsetting')
            ->orderBy('id','desc')
            ->paginate($request->pageSize ?? $request->pageSize);

       // $projects = $projects->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);
        foreach ($projects as $k => $v){
            $v->position_count = Position::where('project_id',$v->id)->count();
            //设备数 ??
            $v->device_count = Position::where('project_id',$v->id)->where('status',1)->count();
        }
        return ProjectsResources::collection($projects);
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
    public function show(Projects $project,ProjectsStages $projectsStages)
    {

        $data = $project->load(['stages','stages.thresholds'])->load(['position','position.areas'])->load('customs')->load('areas','areas.file');
        $date = $projectsStages->where('project_id',$project->id);
        $data['start_time'] = $date->min('start_date');
        $data['end_time']   = $date->max('end_date');
        //项目自定义阈值覆盖标准阈值
        foreach ($data["stages"] as $k => &$v) {
            $rs=ProjectsThresholds::where(["project_id"=>$v["project_id"],"stage_id"=>$v["id"]])->get();
            if(isset($rs[0])){
                  $v["thresholds"]["thresholdinfo"]=$rs[0]->thresholdinfo;
                  $v["thresholds"]["thresholds_id"]=$rs[0]->id;
            }else{
                  $v["thresholds"]["thresholds_id"]=0;
            }

        }
        return new ProjectsResources($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Projects $project,ProjectsStages $projectsStages)
    {
        $data = $project->load(['stages','stages.thresholds'])->load(['position','position.areas'])->load('customs')->load('areas','areas.file');
        $date = $projectsStages->where('project_id',$project->id);
        $data['start_time'] = $date->min('start_date');
        $data['end_time']   = $date->max('end_date');
        return new ProjectsResources($data);
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
        return new ProjectsResources($project->load('customs')->load('areas')->load('stages')->load('position'));
    }

    /**
     * 项目详情 区域列表
     *
     * @param $project
     * @param ProjectsAreas $projectsAreas
     * @param Request $request
     * @return ProjectsResources
     */
    public function area($project,ProjectsPositions $positions,Request $request){
        $positions = $positions->with(['area','area.file']);
        $positions = $positions->where('project_id',$project);
        return new PorjectsAreasResource($positions->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize));
    }

}
