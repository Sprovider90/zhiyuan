<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-08-10
 * Time: 09:28
 */

namespace App\Http\Controllers\Api;
use App\Facades\Common;
use App\Http\Requests\Api\PositiondatasRequest;
use App\Models\Projects;
use App\Models\ProThresholdsLog;
use Excel;
use App\Exports\BaseExport;
use Symfony\Component\HttpKernel\Exception\HttpException;
class PositiondatasController extends Controller
{
    static $proThresholdsLog = [];
    static $proInfo = [];
    public function index(PositiondatasRequest $request)
    {
        $params=[];

        $params["page"]=$request->page?$request->page:1;
        $params["pageSize"]=$request->pageSize;
        $params["type"]=$request->type?$request->type:1;
        $params["startTime"]=$request->startTime;
        $params["endTime"]=$request->endTime;
        $params["monitorId"]=$request->monitorId;
        $url=config("javasource.original.url");

        $result = Common::curl($url, $params, false);

        if(!empty($result)){
            $tmp=json_decode($result,true);

            if($tmp["body"]["list"]){
                foreach ($tmp["body"]["list"] as $k=>&$v){
                    //判断指标是否污染
                    $v["red"]=0;
                        //$this->getRed($v);
                    //list($v["project_tvoc"],$v["project_hcho"])=$this->getPro($v["projectId"]);

                }
                $result=json_encode($tmp);
            }
        }
        return $result;
    }

//    protected function getPro($projectId)
//    {
//        if(!empty(self::$proInfo)){
//            return self::$proInfo;
//        }
//
//        $rs=Projects::find($projectId,["tvoc","hcho"]);
//        self::$proInfo=[$rs->tvoc,$rs->hcho];
//        return self::$proInfo;
//    }
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
                    if($zhibiao[1]<=$positiondata[strtolower($k)]){
                        $result[]=strtolower($k);
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
    public function export(PositiondatasRequest $request)
    {
        $params=[];
        $params["page"]=$request->page?$request->page:1;
        $params["pageSize"]=$request->pageSize;
        $params["type"]=$request->type?$request->type:1;
        $params["startTime"]=$request->startTime;
        $params["endTime"]=$request->endTime;
        $params["monitorId"]=$request->monitorId;
        $url=config("javasource.original.url");
        $result = Common::curl($url, $params, false);
        $arr=json_decode($result,true);
        if(!empty($arr["body"]["list"])){
            $export_data=[];
            $export_data[]=['统计时间','甲醛（mg/m3）','PM2.5（μg/m3）','TVOC（mg/m3）','CO2（ppm）','温度（℃）','湿度（%RH）'];
            foreach ($arr["body"]["list"] as $k=>$v){
                $export_data[]=[$v["timestamp"],$v["formaldehyde"],$v["pm25"],$v["tvoc"],$v["co2"],$v["temperature"],$v["humidity"]];
            }

            $export = new BaseExport($export_data);

            return Excel::download($export, $arr["body"]["list"][0]["monitorId"].'.xlsx');
        }else{
            return new HttpException(400, '无数据');
        }

    }

}
