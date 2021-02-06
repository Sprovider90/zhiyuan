<?php

namespace App\Http\Controllers\Api;

use App\Exports\BaseExport;
use App\Facades\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilesRequest;
use App\Http\Requests\Api\PositiondatasRequest;
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
use App\Models\Location;
use App\Models\Orders;
use App\Models\Position;
use App\Models\Projects;
use App\Models\ProjectsAreas;
use App\Models\ProjectsPositions;
use App\Models\ProThresholdsLog;
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
    static $proThresholdsLog = [];
    static $proInfo = [];

    public function getNewPosition(Request $request){
//        $position = Position::with(['project'])->where('status',1);
        $position = Position::with(['project']);
        $position = $position->whereHas('project',function($query) use ($request) {
//            $query->whereIn('status',[4,5,6]);
            $request->user()->customer_id && $query->where('customer_id',$request->user()->customer_id);
        });
        $position = $position->orderBy('id','desc')->first();
        return response($position);
    }

    //获取首页 销售额/订单数 type 1销售额/2订单数
    public function getIndexOrderSale(Request $request){
        $type = $request->get('type',1);
        if($request->user()->customer_id){
            $dateList = [];
        }else{
            //判断开始日期 结束日期 是否跨月
            if($request->end_date != $request->start_date){
                $request->start_date = date ("Y-m-d",strtotime($request->start_date));
                $request->end_date = date("Y-m-d",strtotime("-1 day",strtotime(" +1 month",strtotime($request->end_date))));
                $dateList = $this->returnMonthRange($request->start_date,$request->end_date);
                //按月统计
                if( $type == 1 ){
                    $saleDateLsit = DB::select('select SUM(money) as money ,left(date,7) as date1,type FROM finance_logs where date between "'.$request->start_date.'" AND "'.$request->end_date.'"  GROUP BY date1,type ORDER BY date1');
                    $date = array_column($saleDateLsit,'date1');
                    foreach ($dateList as $k => $v) {
                        $dateList[$k]['money'] = 0;
                        if(in_array($v['date'],$date)){
                            foreach ($saleDateLsit as $k1 => $v1){
                                if($v['date'] == $v1->date1){
                                    $v1->type ==1 ? $dateList[$k]['money'] += $v1->money : $dateList[$k]['money'] -= $v1->money;
                                }
                            }
                        }
                    }
                }else{
                    $dateList = $this->returnMonthRange($request->start_date,$request->end_date);
                    $orderDateLsit = DB::select('select count(id) as count ,left(created_at,7) as date FROM projects where created_at between "'.$request->start_date.'" AND "'.$request->end_date.'"   GROUP BY date ORDER BY date');
                    $date = array_column($orderDateLsit,'date');
                    $dateNum = array_column($orderDateLsit,'count','date');
                    foreach ($dateList as $k => $v) {
                        $dateList[$k]['count'] = 0;
                        if(in_array($v['date'],$date)){
                            $dateList[$k]['count'] = $dateNum[$v['date']];
                        }
                    }
                }
            }else{
                $request->start_date = date ("Y-m-d",strtotime($request->start_date));
                $request->end_date = date("Y-m-d",strtotime("-1 day",strtotime(" +1 month",strtotime($request->end_date))));
                //按天统计
                if( $type == 1 ){
                    $dateList = $this->returnDateList($request->start_date,$request->end_date);
                    $saleDateLsit = DB::select('select SUM(money) as money ,date ,type FROM finance_logs where date between "'.$request->start_date.'" AND "'.$request->end_date.'"  GROUP BY date,type ORDER BY date');
                    $date = array_column($saleDateLsit,'date');
                    foreach ($dateList as $k => $v) {
                        $dateList[$k]['money'] = 0;
                        if(in_array($v['date'],$date)){
                            foreach ($saleDateLsit as $k1 => $v1){
                                if($v['date'] == $v1->date){
                                    $v1->type ==1 ? $dateList[$k]['money'] += $v1->money : $dateList[$k]['money'] -= $v1->money;
                                }
                            }
                        }
                    }
                }else{
                    $dateList = $this->returnDateList($request->start_date,$request->end_date);
                   $orderDateLsit = DB::select('select count(id) as count ,left(created_at,10) as date FROM projects where created_at between "'.$request->start_date.'" AND "'.$request->end_date.'"   GROUP BY date ORDER BY date');
                    $date = array_column($orderDateLsit,'date');
                    $dateNum = array_column($orderDateLsit,'count','date');
                    foreach ($dateList as $k => $v) {
                        $dateList[$k]['count'] = 0;
                        if(in_array($v['date'],$date)){
                            $dateList[$k]['count'] = $dateNum[$v['date']];
                        }
                    }
                }
            }
        }
        return response()->json($dateList);
    }
    //获取新增项目统计
    public function getIndexNewProjectCount(Request $request){
        if($request->user()->customer_id){
            if($request->type == 4 || !$request->type){
                $start_date = date("Y-m-d",strtotime(Projects::where('customer_id',$request->user()->customer_id)->min('created_at')));
                $end_date = date("Y-m-d",strtotime(Projects::where('customer_id',$request->user()->customer_id)->max('created_at')));
            }else {
                $pro_date = $this->returnDate($request->type ?? 1);
                $start_date = substr($pro_date[0], 0, 10);
                $end_date = substr($pro_date[1], 0, 10);
            }
            if($request->start_date && $request->end_date){
                $pro_date = $this->returnDateList($request->start_date,$request->end_date);
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $dateList = $this->returnDateList($start_date, $end_date);
            $proDateList = DB::select('select count(id) as num ,left(created_at,10) as date FROM projects where left(created_at,10) between "'.$start_date.'" AND "'.$end_date.'" and  customer_id='.$request->user()->customer_id.' GROUP BY date ORDER BY date');
            $date = array_column($proDateList,'date');
            $dateNum = array_column($proDateList,'num','date');
            foreach ($dateList as $k => $v) {
                $dateList[$k]['count'] = 0;
                if(in_array($v['date'],$date)){
                    $dateList[$k]['count'] = $dateNum[$v['date']];
                }
            }
        }else {
            if($request->type == 4 || !$request->type){
                $start_date = date("Y-m-d",strtotime(Projects::min('created_at'))) ;
                $end_date = date("Y-m-d",strtotime(Projects::max('created_at')));
            }else{
                $pro_date = $this->returnDate($request->type ?? 1);
                $start_date = substr($pro_date[0], 0, 10);
                $end_date = substr($pro_date[1], 0, 10);
            }
            if($request->start_date && $request->end_date){
                $pro_date = $this->returnDateList($request->start_date,$request->end_date);
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $dateList = $this->returnDateList($start_date, $end_date);
            $proDateList = DB::select('select count(id) as num ,left(created_at,10) as date FROM projects where left(created_at,10) between "'.$start_date.'" AND "'.$end_date.'"  GROUP BY date ORDER BY date');
            $date = array_column($proDateList,'date');
            $dateNum = array_column($proDateList,'num','date');
            foreach ($dateList as $k => $v) {
                $dateList[$k]['count'] = 0;
                if(in_array($v['date'],$date)){
                    $dateList[$k]['count'] = $dateNum[$v['date']];
                }
            }
        }
        return response()->json($dateList);
    }

    //获取最新监测点监测数据
    public function getNewPositionData(Request $request){
        $params["type"]=$request->type ? $request->type:2;
        $params["monitorId"]= $request->monitorId;
        $params["startTime"]= date( 'Y-m-d H:i:s', strtotime('-1 month'));
        $params["endTime"]  = date("Y-m-d H:i:s");
        $params["page"]     = 1;
        $params["pageSize"] = 1;
        $url=config("javasource.original.url");
        $result = Common::curl($url, $params, false);
        $res = [];
        if(!empty($result)){
            $tmp=json_decode($result,true);
            if($tmp["body"]["list"]){
                /*foreach ($tmp["body"]["list"] as $k=>&$v){
                    //判断指标是否污染
                    $v["red"]=$this->getRed($v);
                }*/
                $res['data'] = $tmp["body"]["list"];
            }else{
                $res['data'] = [];
            }
        }
        $res['position'] = ProjectsPositions::find($request->monitorId);
        $res['area']    = ProjectsAreas::find($res['position']['area_id']);
        $res['project']  = Projects::find($res['position']['project_id']);
        $data = $this->getProjectThreshold($res['position']['project_id']);
        $thresholdinfo_data = json_decode($data->thresholdinfo,true);
        $res['position']['tag']  =  1;
        $res['project']['threshold_name'] = $data->thresholds_name ?? '';
        $res['project']['threshold'] = $data;
        if($res['data']){
            if($thresholdinfo_data){
                foreach ($thresholdinfo_data as $k => $v){
                    if($k == 'co2' || $k == 'pm25'){
                        $k = strtoupper($k);
                    }
                    $arr = explode('~',$v);
                    switch ($res['data']['0'][$k]){
                        case $res['data']['0'][$k] < $arr[0]:
                            $res['data']['0'][$k.'_tag'] = 1;
                            break;
                        case $res['data']['0'][$k] >= $arr[0] && $res['data']['0'][$k] < $arr[1]:
                            $res['data']['0'][$k.'_tag'] = 2;
                            $res['position']['tag'] == 1 && $res['position']['tag'] = 2;
                            break;
                        case $res['data']['0'][$k] >= $arr[1]:
                            $res['data']['0'][$k.'_tag'] = 3;
                            $res['position']['tag'] == 1 || $res['position']['tag'] == 2 && $res['position']['tag'] = 3;
                            break;
                    }
                }
            }
        }
        return response()->json($res);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 批量获取监测点最新的数据
     */
    public function getNewPositionDatas(Request $request){

        $params["monitorIds"]= $request->monitorIds;
        $params["startTime"]= date( 'Y-m-d H:i:s', strtotime('-1 day'));
        $params["endTime"]  = date("Y-m-d H:i:s");

        $url=config("javasource.original.furl");
        $result = Common::curl($url, $params, false);
        return $result;

    }
    //获取所有项目列表 首页大屏使用
    public function getIndexProjectList(Request $request,Projects $projects){
        $projects = $projects->with(['areas','areas.file','stages']);
        $request->user()->customer_id && $projects->where('customer_id',$request->user()->customer_id);
        $request->user()->show_project_id && $projects = $projects->selectRaw('*,if(id='.$request->user()->show_project_id.',1,0) as order_num')->orderBy('order_num','desc');
        $projects = $projects->whereIn('status',[4,5,6])->orderBy('id','desc')->get();
        return response()->json($projects);
    }

    //通过项目ID获取 项目 区域 空气质量列表
    public function getIndexProjectAreaList(Request $request,Projects $projects)
    {
        $projects = $projects->with(['areas.file','stages'])->where('id',$request->project_id)->whereIn('status',[4,5,6])->with(['areas' => function($query){
            return $query->orderBy('id','desc');
        }]);
        $request->user()->customer_id && $projects = $projects->where('customer_id',$request->user()->customer_id);
        $projects = $projects->first();
        $thresholds_data = $this->getProjectThreshold($request->project_id);
        if($projects){
            foreach ($projects['areas'] as $k => $v){
                $v->threshold_name = $thresholds_data ? $thresholds_data->thresholds_name : '';
                $thresholds = Thresholds::where('name',$thresholds_data->thresholds_name)->first();
                $v->descript = $thresholds->descript;
                $tag = Tag::where('model_type',2)->where('model_id',$v->id)->orderBy('id','desc')->first();
                $v['tag'] =  null;
                if($tag){
                    $v['tag'] = $tag->air_quality;
                }
                //所有点位
                $list = ProjectsPositions::where('area_id',$v->id)->whereIn('status',[1,2]);
                $position = $list->get();
                $p_id_str = $list->get(['id']);
                $v->position = $position;
                if($position){
                    foreach ($v->position as $k1 => $v1){
                        $tag = Tag::where('model_type',3)->where('model_id',$v1->id)->orderBy('id','desc')->first();
                        $v1['tag'] =  null;
                        if($tag){
                            $v1['tag'] = $tag->air_quality;
                        }
                        $v1['location'] = Location::where('position_id',$v1->id)->first();
                    }
                }
                //解决方案
                $w_list = Warnigs::where('project_id',$request->project_id)->whereIn('point_id',$p_id_str)->get(['id']);
                $msg = WarnigsSms::whereIn('warnig_id',$w_list)->orderBy('id','desc')->first();
                if($msg && isset($msg->pics) && !empty($msg->pics)){
                    $msg['pics_img'] = Files::whereIn('id',explode(",",$msg->pics))->first();
                }
                $v['warnigs_sms'] = $msg;
            }
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
        //本周项目总数
        $bz_date = $this->returnDate(1);
        $bz_pro_count = Projects::where($where)->whereBetween('created_at',$bz_date)->count();
        //预警警报 / 解决方案
        $Warnigs = Warnigs::query();
        if($request->user()->customer_id){
            $projects=Projects::where("customer_id",$request->user()->customer_id)->pluck("id")->toArray();
            if(!empty($projects)){
                $Warnigs = Warnigs::whereIn('project_id',$projects);
            }else{
                $Warnigs = Warnigs::whereIn('project_id',[]);
            }
        }
        $Warnigs = $Warnigs->where('threshold_keys', '!=' , "");
        $msg_list = $Warnigs->with(['project','project.customs','projectsPositions'])->with(['projectsPositions.area'=>function($query){
            $query->withTrashed();}])->orderBy('id','desc')->limit(10)->get();
        $updatefeid="nocustomerred";
        if($request->user()->customer_id){
            $updatefeid="customerred";
        }

        foreach ($msg_list as $k => $v){
            $v->smscount = WarnigsSms::where('warnig_id',$v->id)->count();
            $fis=WarnigsSms::where(['warnig_id'=>$v->id,$updatefeid=>0])->first(["id"]);
            $v->isnew=isset($fis->id)?1:0;
            $v->threshold_keys = $this->getChinaName($v->threshold_keys);
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
                'order_count_list'      => [],
                //项目累计
                'project_count'         => $project_count,
                //项目本周新增
                'project_week_count'    => $bz_pro_count,
                //项目
                'project_count_list'    => [],
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
//        $customer_id = $request->get('customer_id','');
//        $device_id = $request->get('device_id','');
//        $customer_id && $device =  $device->where('customer_id',$customer_id);
        $device = $device->where(function ($query) use ($request) {
            $query->where(function ($query1) use ($request) {
                $request->customer_id && $query1->where('customer_id',$request->customer_id);
                $query1->where('status',1);
            });
            $request->device_id && $query->orWhere(function ($query1) use ($request){
                $request->device_id &&  $query1->where('id',$request->device_id);
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

    /*public function devices(Request $request,Device $device){
        if($request->customer_id){
            $customered_ids = Customers::where('id',$request->customer_id)->pluck('id');
            $posionsed  = Position::whereIn('project_id',$customered_ids)->pluck('device_id');
        }else{
            $posionsed  = null;
        }
        $device = $device->where(function ($query) use ($request,$posionsed) {
            $query->where(function ($query1) use ($request,$posionsed) {
                $request->customer_id && $query1->where(function ($qy) use ($request,$posionsed){
                    $posionsed && $qy->where('customer_id',$request->customer_id)->orWhereIn('id',$posionsed);
                });
                $query1->where('status',1);
            });
            $request->device_id && $query->orWhere(function ($query1) use ($request){
                $request->device_id &&  $query1->where('id',$request->device_id);
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
    }*/

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
        $type = $request->get('type',0); //0图片 1视频 2base64文件上传
        if($type ==2 ){
            $file = $request->get('file');
            $file = explode(',',$file)[1];
            $img_len = strlen($file);
            $fileSize = number_format(($img_len - ($img_len / 8) * 2 / 1024), 2);
            $fileExt  = 'png';
            $fileName = $clientName =date("YmdHis").rand(10000000,99999999).'.'.$fileExt;
            $fileMime = 'image/png';
            $fileData = base64_decode($file);
        }else{
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
            $fileSize = sprintf("%.2f",round($file->getSize()/1024,2));
            $fileExt = strtolower($file->getClientOriginalExtension());
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
            $fileData = file_get_contents($fileTmp);
        }
        $fileUpFlg = Storage::disk('public')->put($fileName,$fileData);
        $filePath = Storage::disk('public')->url($fileName);
        if($fileUpFlg){
            #插入数据库
            $file = Files::create([
                'name'          =>  $fileName,
                'size'          =>  $fileSize,
                'ext'           =>  $fileExt,
                'path'          =>  $filePath,
                'mime'          =>  $fileMime,
                'upload_name'   =>  $clientName
            ]);
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

    protected function getRed($positiondata)
    {
        $result=[];
        $proinfo=$this->getProThresholds($positiondata["projectId"]);

        if(!empty($proinfo)){
            $pipei_data=$proinfo->last();

            if(!empty($pipei_data)){
                foreach ($proinfo as $k=>$v){
                    if($positiondata["timestamp"]>=$v->created_at){
                        $pipei_data=$v;
                        break;
                    }
                }

                foreach ($pipei_data->thresholdinfo as $k=>$v){
                    $zhibiao=explode("~",$v);
                    if($zhibiao[1]<=$positiondata[$k]){
                        $result[]=$k;
                    }
                }
            }
        }
        return $result;
    }
    protected function getProThresholds($project_id)
    {
        $result=[];
        if(!empty(self::$proThresholdsLog)){
            return self::$proThresholdsLog;
        }
        $result=ProThresholdsLog::where("project_id",$project_id)->orderBy('created_at','desc')->get();

        if(!empty($result)){
            foreach ($result as $k=>$v){
                $v->thresholdinfo=json_decode($v->thresholdinfo,true);
            }
            self::$proThresholdsLog=$result;
        }
        return $result;

    }

}
