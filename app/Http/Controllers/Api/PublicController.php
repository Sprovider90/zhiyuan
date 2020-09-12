<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilesRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\FilesResource;
use App\Http\Resources\OrdersResources;
use App\Http\Resources\ProjectsAreasResource;
use App\Http\Resources\ThresholdsResource;
use App\Models\Customers;
use App\Models\Device;
use App\Models\Files;
use App\Models\FinanceLog;
use App\Models\Orders;
use App\Models\Projects;
use App\Models\ProjectsAreas;
use App\Models\ProjectsPositions;
use App\Models\Tag;
use App\Models\Thresholds;
use App\Models\Warnigs;
use App\Models\WarnigsSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublicController extends Controller
{
    //获取所有项目列表 首页大屏使用
    public function getIndexProjectList(Request $request,Projects $projects){
        $projects = $projects->with(['areas','areas.file','stages']);
        $request->user()->customer_id && $projects->where('customer_id',$request->user()->customer_id);
        $request->user()->show_project_id && $projects = $projects->selectRaw('*,if(id='.$request->user()->show_project_id.',1,0) as order_num')->orderBy('order_num','desc');
        $projects = $projects->whereIn('status',[1,4,5,6])->orderBy('created_at','desc')->get();
        if($projects[0]['areas']){
            foreach ($projects[0]['areas'] as $k => $v){
                $tag = Tag::where('model_type',2)->where('model_id',$v->id)->orderBy('created_at','desc')->first();
                $v['tag'] =  null;
                if($tag){
                    $v['tag'] = $tag->air_quality;
                }
                //所有点位
                $position = ProjectsPositions::where('area_id',$v->id)->where('status',1)->get();
                $v->position = $position;
                if($position){
                    foreach ($v->position as $k1 => $v1){
                        $tag = Tag::where('model_type',3)->where('model_id',$v1->id)->orderBy('created_at','desc')->first();
                        $v1['tag'] =  null;
                        if($tag){
                            $v1['tag'] = $tag->air_quality;
                        }
                    }
                }
            }
            //解决方案
            $w_list = Warnigs::where('project_id',$projects[0]['id'])->get(['id']);
            $msg = WarnigsSms::whereIn('warnig_id',$w_list)->orderBy('id','desc')->first();
            $projects[0]['warnigs_sms'] = $msg;
            //检测标准
            if($projects[0]['status'] == 1){
                $projects[0]['threshold'] = null;
            }else{
                $projects[0]['threshold'] = $this->getProjectStageThreshold($projects[0]['stages']);
            }
        }
        return response()->json($projects);
    }

    //通过项目ID获取 项目 区域 空气质量列表
    public function getIndexProjectAreaList(Request $request,Projects $projects)
    {
        $projects = $projects->with(['areas','areas.file','stages'])->where('id',$request->project_id)->whereIn('status',[1,4,5,6]);
        $request->user()->customer_id && $projects = $projects->where('customer_id',$request->user()->customer_id);
        $projects = $projects->first();
        if($projects){
            foreach ($projects['areas'] as $k => $v){
                $tag = Tag::where('model_type',2)->where('model_id',$v->id)->orderBy('created_at','desc')->first();
                $v['tag'] =  null;
                if($tag){
                    $v['tag'] = $tag->air_quality;
                }
            }
            //解决方案
            $w_list = Warnigs::where('project_id',$projects['id'])->get(['id']);
            $msg = WarnigsSms::whereIn('warnig_id',$w_list)->orderBy('id','desc')->first();
            $projects['warnigs_sms'] = $msg;
            //检测标准
            if($projects->status == 1){
                $projects->threshold = null;
            }else{
                $projects->threshold = $this->getProjectStageThreshold($projects->stages);
            }
        }else{
            $projects = [];
        }
        return response()->json($projects);
    }

    //首页 项目总数 点位总数  设备总数
    public function getIndexCount(Request $request){
        $order_start = $request->get('order_start','');
        $order_end   = $request->get('order_end','');

        $pro_start  = $request->get('pro_start','');
        $pro_end    = $request->get('pro_end','');
        $type       = $request->get('type',1);

        $where = [];
        $request->user()->customer_id && $where[] = ['customer_id',$request->user()->customer_id];
        //项目总数
        $project_count = Projects::where($where)->count();
        //点位总数
        if(!$request->user()->customer_id){
            $position_count = ProjectsPositions::count();
        }else{
            $project = Projects::where($where)->get(['id']);
            $position_count = ProjectsPositions::whereIN('project_id',$project)->count();
        }
        //设备总数
        $device_count = Device::where($where)->count();
        //运行设备
        $run_device_count = Device::where($where)->where('run_status',1)->count();

        if($request->user()->customer_id){
            $dateList = [];
        }else{
            //销售额 默认本月
            $date = $this->returnDate(3);
            $dateList = $this->returnDateList(substr($date[0],0,10),substr($date[1],0,10));
            foreach ($dateList as $k => $v){
                $dateList[$k]['money'] = 0;
                $dateList[$k]['order_count'] = 0;
                $s = FinanceLog::where('type',1)->where('date',$v['date'])->sum('money');
                $t = FinanceLog::where('type',2)->where('date',$v['date'])->sum('money');
                $dateList[$k]['money'] = $s-$t;
                $count = Orders::whereRaw('left(created_at,10)="'.$v['date'].'"')->count();
                $dateList[$k]['order_count'] = $count ?? 0;
            }
            /*$date = $this->returnDate(2);
            if(($order_start && $order_end) && (strtotime($order_start) < strtotime($order_end))){
                $date = [$order_start,$order_end];
                $dateList = $this->returnMonthList($date[0],$date[1]);
                foreach ($dateList as $k => $v){
                    $dateList[$k]['money'] = 0;
                    $dateList[$k]['order_count'] = 0;
                    $s = FinanceLog::where('type',1)->whereRaw('left(date,7)="'.$v['date'].'"')->sum('money');
                    $t = FinanceLog::where('type',2)->whereRaw('left(date,7)="'.$v['date'].'"')->sum('money');
                    $dateList[$k]['money'] = $s-$t;
                    $count = Orders::whereRaw('left(created_at,7)="'.$v['date'].'"')->count();
                    $dateList[$k]['order_count'] = $count ?? 0;
                }
            }else{
                if(($order_start && $order_end)){
                    $date = [$order_start,$order_end];
                }
                $dateList = $this->returnDateList(substr($date[0],0,10),substr($date[1],0,10));
                foreach ($dateList as $k => $v){
                    $dateList[$k]['money'] = 0;
                    $dateList[$k]['order_count'] = 0;
                    $s = FinanceLog::where('type',1)->where('date',$v['date'])->sum('money');
                    $t = FinanceLog::where('type',2)->where('date',$v['date'])->sum('money');
                    $dateList[$k]['money'] = $s-$t;
                    $count = Orders::whereRaw('left(created_at,10)="'.$v['date'].'"')->count();
                    $dateList[$k]['order_count'] = $count ?? 0;
                }
            }*/
        }

        //本周项目总数
        $bz_date = $this->returnDate(1);
        $bz_pro_count = Projects::where($where)->whereBetween('created_at',$bz_date)->count();
        //项目数据表
        if($request->user()->customer_id){
            $proDateList = [];
        }else {
            $pro_date = $this->returnDate(3);
            $proDateList = $this->returnDateList(substr($pro_date[0], 0, 10), substr($pro_date[1], 0, 10));
            /*if(($pro_start && $pro_end) && (strtotime($pro_start) < strtotime($pro_end))){
                $proDateList = $this->returnDateList($pro_start,$pro_end);
            }else{
                $pro_date = $this->returnDate($type);
                $proDateList = $this->returnDateList(substr($pro_date[0], 0, 10), substr($pro_date[1], 0, 10));
            }*/
            foreach ($proDateList as $k => $v) {
                $proDateList[$k]['count'] = Projects::whereRaw('left(created_at,10)="' . $v['date'] . '"')->count() ?? 0;
            }
        }
        //预警警报
        if($request->user()->customer_id){
            $projects=Projects::where("customer_id",$request->user()->customer_id)->pluck("id")->toArray();
            if(!empty($projects)){
                $Warnigs = Warnigs::whereIn('project_id',$projects);
            }else{
                $Warnigs = Warnigs::whereIn('project_id',[]);
            }

            $msg_list = $Warnigs->where('threshold_keys', '!=' , "")->with(['projectsPositions.area'=>function($query){
                $query->withTrashed();
            }
            ])->orderBy('id','desc')->limit(10)->get();
            foreach ($msg_list as $k => $v){
                $v->smscount = WarnigsSms::where('warnig_id',$v->id)->count();
                $v->isnew=1;
                $v->threshold_keys = $this->getChinaName($v->threshold_keys);
            }
        }else{
            //解决方案
            $msg_list = Warnigs::where('threshold_keys', '!=' , "")->with(['projectsPositions.area'=>function($query){
                $query->withTrashed();
            }
            ])->orderBy('id','desc')->limit(10)->get();
            foreach ($msg_list as $k => $v){
                $v->smscount = WarnigsSms::where('warnig_id',$v->id)->count();
                $v->isnew=1;
                $v->threshold_keys = $this->getChinaName($v->threshold_keys);
            }
            /*$sms_id_list = WarnigsSms::orderBy('id','desc')->limit(10)->get(['warnig_id']);
            $msg_list = Warnigs::with(['projectsPositions.area'=>function($query){
                $query->withTrashed();
            }
            ])->whereIN('id',$sms_id_list)->orderBy('id','desc')->get();
            foreach ($msg_list as $k => $v){
                $v->smscount = WarnigsSms::where('warnig_id',$v->id)->count();
                $v->isnew=1;
                $v->threshold_keys = $this->getChinaName($v->threshold_keys);
            }*/
        }
        return response()->json(array(
                //项目总数 点位总数 设备总数 运行设备数
                'count' => array(
                    'project_count'     => $project_count,
                    'position_count'    => $position_count,
                    'device_count'      => $device_count,
                    'run_device_count'  => $run_device_count,
                ),
                //销售额 订单数
                'order_count_list'      => $dateList,
                //项目累计
                'project_count'         => $project_count,
                //项目本周新增
                'project_week_count'    => $bz_pro_count,
                //项目
                'project_count_list'    => $proDateList,
                //预警方案/解决方案
                'msg_list'              => $msg_list
         ));
    }

    //获取所有 状态 正常  已停止 没有绑定客户的列表
    public function getAllNoCustomerDevicesList(Request $request,Device $device){
        return new DeviceResource($device->whereNull('customer_id')->where('status',1)->orderBy('id','desc')->get());
    }

    //对应项目设备列表
    public function devices(Request $request,Device $device){
        $customer_id = $request->get('customer_id','');
        $device_id = $request->get('device_id','');
        $customer_id && $device =  $device->where('customer_id',$customer_id);
        $device = $device->where(function ($query) use ($customer_id,$device_id) {
            $query->where(function ($query1) use ($customer_id) {
                $customer_id && $query1->where('customer_id',$customer_id);
                $query1->where('status',1);
            });
            $device_id && $query->orWhere(function ($query1) use ($device_id){
                   $device_id &&  $query1->where('id',$device_id);
            });
        });
        $device = $device->orderBy('id','desc')->get();
        foreach ($device as $k => $v){
            $flg = ProjectsPositions::where('device_id',$v->id)->count();
            $v->position_flg = 0;
            if($flg){
                $v->position_flg = 1;
            }
        }
        return new OrdersResources($device);
    }

    //对应项目区域列表
    public function areas(Request $request,ProjectsAreas $projectsAreas){
        $project_id = $request->get('project_id','');
        $project_id && $projectsAreas = $projectsAreas->where('project_id',$project_id);
        return new ProjectsAreasResource($projectsAreas->where('file','>',0)->orderBy('id','desc')->get());
    }

    //监测标准列表
    public function thresholds(Thresholds $thresholds){
        return new ThresholdsResource($thresholds->where('status',1)->orderBy('id','desc')->get(['id','name','status','thresholdinfo']));
    }

    //客户列表
    public function customers(Customers $customers){
        return new CustomersResources($customers->orderBy('id','desc')->get(['id','company_name','company_addr']));
    }


    // 图片上传
    public function store(FilesRequest $request){
        $file = $request->file('file');
        $type = $request->get('type',0); //0图片 1视频

        $fileSize = sprintf("%.2f",round($file->getSize()/1024,2));
        $fileExt = strtolower($file->getClientOriginalExtension());

        switch ($type){
            case 0:
                //校验图片格式 图片大小
                $configFileMaxSize = config('filesystems.UPLOAD_IMAGE_MAX_SIZE');
                $configFileExt = config('filesystems.UPLOAD_IMAGE_EXT');
                break;
            case 1:
                //校验视频格式 视频大小
                $configFileMaxSize = config('filesystems.UPLOAD_VIDEOS_MAX_SIZE');
                $configFileExt = config('filesystems.UPLOAD_VIDEOS_EXT');
                break;
        }

        if(round($fileSize/1024/1024,2) > $configFileMaxSize){
            throw new HttpException(403, '文件最大不超过'.$configFileMaxSize.'M');
        }
        if(!in_array(strtolower($fileExt),explode(',',$configFileExt))){
            throw new HttpException(403, '文件不在允许的'.$configFileExt.'扩展中');
        }
        $clientName = $file->getClientOriginalName();
        $fileTmp = $file->getRealPath();
        $fileName = date("YmdHis").rand(10000000,99999999).'.'.$fileExt;
        $fileMime = $file->getMimeType();
        $fileUpFlg = Storage::disk('public')->put($fileName,file_get_contents($fileTmp));
        $filePath = Storage::disk('public')->url($fileName);
        if($fileUpFlg){
            #插入数据库
            $file = Files::create([
                'name'          =>  $fileName,
                'size'          =>  $fileSize,
                'ext'           =>  $fileExt,
                'path'          =>  $filePath,
                'mime'          =>  $fileMime,
                'upload_name'   =>  $clientName]);
            return response(new FilesResource($file),201);

        }else{
            return new HttpException(400, '内部错误');
        }

    }

    /**
     * 获取阶段检测标准
     * @param $data
     * @return null
     */
    private function getProjectStageThreshold($data){
        foreach ($data as $k => $v){
            if(time() > strtotime($v->start_date) && time() < strtotime($v->end_date)){
               $data = Thresholds::find($v->threshold_id);
               return $data ? $data->thresholdinfo : null;
            }
        }
    }
}
