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
    /**
     * 统计状态
     * $type  1本周 2本月 3今年 默认2
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Projects $projects,Request $request){
        $date = $this->returnDate($request->type ?? 2);
        //进行中 1暂停中 4施工中 5交付中 6维护中
        $ongoing_count   = $projects->whereBetween('created_at',$date)->whereIn('status',[1,4,5,6])->count();
        //已结束 2已结束
        $over_count  = $projects->whereBetween('created_at',$date)->where('status',2)->count();
        //未开始 0未开始
        $not_started_count   = $projects->whereBetween('created_at',$date)->where('status',0)->count();
        //项目总数量
        $all_count  = $projects->whereBetween('created_at',$date)->whereIn('status',[0,1,2,4,5,6])->count();
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

    /***
     * 返回开始结束日期
     *
     * @param int $type 1 本周 2 本月 3本年度
     * @return array 【开始日期,结束日期】
     */
    private function returnDate($type = 1){
        $type = ($type < 1  || $type > 3) ? 1 : $type ;
        $start 	= '';
        $end 	= '';
        switch ($type) {
            case 1:
                $start 	= date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
                $end 	= date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
                break;

            case 2:
                $start 	= date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
                $end 	= date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
                break;
            case 3:
                $start 	= date('Y-m-d 00:00:00',strtotime(date("Y",time())."-1"."-1")); //本年开始
                $end 	= date('Y-m-d 23:59:59',strtotime(date("Y",time())."-12"."-31")); //本年结束
                break;
        }
        return [$start,$end];
    }
}
