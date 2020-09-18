<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PreinstallResource;
use App\Http\Resources\ProjectsResources;
use App\Imports\DeviceImport;
use App\Models\Device;
use App\Models\Preinstall;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PreinstallController extends Controller
{
    //
    public function index(Request $request , Projects $projects){
        $projects = $projects->with(['customs']);
        //增加客户登录
        $request->user()->customer_id && $projects = $projects->where('customer_id',$request->user()->customer_id);
        //状态0未开始1暂停中2已结束3项目错误4施工中5交付中6维护中7项目大阶段错误
        isset($request->status) && $request->status !=='' && $projects = $projects->where('status',$request->status);
        $request->setting_flg && $projects = $projects->where('setting_flg',$request->setting_flg);
        $request->name   && $projects = $projects->where('name','like',"%{$request->name}%");
        $projects = $projects
            ->orderBy('id','desc')
            ->paginate($request->pageSize ?? $request->pageSize);
        return ProjectsResources::collection($projects);
    }

    public function show($projectId,Request $request){
        return new PreinstallResource(Preinstall::where('project_id',$projectId)->orderBy('date','desc')->paginate($request->pageSize ?? $request->pageSize));
    }

    public function import(Projects $project,Request $request,Excel $excel){
        $file = $request->file('file');
        if(!$file){
            return response()->json(['message' => '请选择要导入的Excel文件'],404);
        }
        $data = Excel::toArray(new DeviceImport(), $file);
        unset($data[0][0]);
        try{
            DB::beginTransaction();
            if(count($data[0]) == 0){
                return response()->json(['message' => '请填写导入数据'],404);
            }
            //删除原有数据 进行覆盖操作
            Preinstall::where('project_id',$project->id)->delete();
            foreach ($data[0] as $row) {
                $date = date("Y-m-d",intval(($row[0] - 25569) * 3600 * 24));
                Preinstall::where('project_id',$project->id)->where('date',$date)->delete();
                $model = new Preinstall();
                $flg = $model->create(
                    [
                        'project_id'            => $project->id,
                        'customer_id'           => $project->customer_id,
                        'date'                  => $date,
                        'hcho'                  => $row[1],
                        'tvoc'                  => $row[2],
                    ]
                );
                if(!$flg){
                    DB::rollBack();
                    return $this->errorResponse(500,'系统错误');
                }
            }
            //更新项目表setting_flg=2
            Projects::where('id',$project->id)->update(['setting_flg'=>2]);
            DB::commit();
            return response('',201);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse(500,$e->getMessage());
        }
    }


}
