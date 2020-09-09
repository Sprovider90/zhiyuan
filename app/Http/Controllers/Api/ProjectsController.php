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
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        isset($request->status) && $request->status !=='' && $projects_query=$projects->where('status',$request->status);
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
        $projects = $projects->load('areas')->load('stages');
        //修改项目状态
        $projects->update(['status'=>$this->getProjectStatus($projects->stages)]);
        dispatch(new UpdateProStage(["project_id"=>$projects->id]));
        return response(new ProjectsResources($projects));
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
        foreach ($data["stages"] as $k => $v) {
            $rs=ProjectsThresholds::where(["project_id"=>$v["project_id"],"stage_id"=>$v["id"]])->get();
            if(isset($rs[0])){
                  $v["pro_thresholdinfo"]=$rs[0]->thresholdinfo;
                  $v["pro_thresholds_id"]=$rs[0]->id;
            }else{
                  $v["pro_thresholds_id"]=0;
            }
        }
        foreach ($data['areas'] as $k => $v){
            $flg = ProjectsPositions::where('area_id',$v->id)->where('project_id',$project->id)->count();
            $v['edit_flg'] = true;
            $flg && $v['edit_flg']=false;
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
       /* DB::transaction(function() use ($request, &$project){
            $project->update($request->all());
            $project->areas()->delete();
            $project->stages()->delete();
            $project->areas()->createMany(json_decode($request->areas,true));
            $project->stages()->createMany(json_decode($request->stages,true));
        });*/
        DB::beginTransaction();
        try {
            $project->update($request->all());
            //阶段处理 要求不变更阶段id
            $stages_data = json_decode($request->stages,true);
            foreach ($stages_data as $k => $v){
                $id = '';
                if(isset($v['id'])){
                    $id = $v['id'] ;
                    unset($v['id']);
                }
                if($id > 0){
                    //判断是否与原来阈值id一样 不一样则进行删除projects_thresholds
                    $tmp = ProjectsStages::find($id);
                    if($v['threshold_id'] && $v->threshold_id !== $tmp->threshold_id){
                        //真删projects_thresholds
                        ProjectsThresholds::where('project_id',$project->id)->where('stage_id',$v->id)->forceDelete();
                    }
                    ProjectsStages::where('id',$id)->update($v);
                }else{
                    $v['project_id'] = $project['id'];
                    ProjectsStages::create($v);
                }
            }
            //阶段删除处理
            $del_stages = $request->stages_del;
            if($del_stages){
                ProjectsStages::where('project_id',$project['id'])->whereIN('id',explode(',',$del_stages))->delete();
            }
            //区域处理
            $areas_data = json_decode($request->areas,true);
            foreach ($areas_data as $k => $v){
                $id = '';
                if(isset($v['id'])){
                    $id = $v['id'] ;
                    unset($v['id']);
                }
                if($id > 0){
                    ProjectsAreas::where('id',$id)->update($v);
                }else{
                    $v['project_id'] = $project['id'];
                    ProjectsAreas::create($v);
                }
            }
            //区域删除处理
            $del_areas = $request->areas_del;
            if($del_areas){
                ProjectsAreas::where('project_id',$project['id'])->whereIN('id',explode(',',$del_areas))->delete();
            }
            $project = $project->load('areas')->load('stages');
            //修改项目状态
            $project->update(['status'=>$this->getProjectStatus($project->stages)]);
            DB::commit();
            dispatch(new UpdateProStage(["project_id"=>$project->id]));
            return response(new ProjectsResources($project),201);
        }catch (\Exception $e){
            DB::rollback();
            throw new HttpException(403, $e->getMessage());
        }
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

    /**
     * 新增项目或编辑项目时判断当前所属状态
     * 状态0未开始1暂停中2已结束3项目错误4施工中5交付中6维护中7项目大阶段错误
     */
    private function getProjectStatus($data){
        $count = count($data);
        //未开始
        if(time() < strtotime($data[0]['start_date'])){
            return 0;
        }
        if(time() > strtotime($data[$count-1]['end_date'])){
            return 2;
        }
        foreach ($data as $k => $v){
            if(time() > strtotime($v->start_date) && time() < strtotime($v->end_date)){
                return $v->stage;
            }
        }
        return 1;
    }

}
