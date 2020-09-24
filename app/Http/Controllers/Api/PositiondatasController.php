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
use App\Models\ProjectsAreas;
use App\Models\ProjectsPositions;
use App\Models\ProThresholdsLog;
use Excel;
use App\Exports\PositiondatasExport;
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
            $export_data[]=['统计时间','甲醛（mg/m3）','TVOC（mg/m3）','PM2.5（μg/m3）','CO2（ppm）','温度（℃）','湿度（%RH）'];
            foreach ($arr["body"]["list"] as $k=>&$v){
                $export_data[]=[$v["timestamp"],$v["formaldehyde"],$v["TVOC"],$v["PM25"],$v["CO2"],$v["temperature"],$v["humidity"]];
            }
            $export = new PositiondatasExport($export_data,array_column($arr["body"]["list"],"red"));
            $rs=ProjectsPositions::where('id', $arr["body"]["list"][0]["monitorId"])->first();
            return Excel::download($export, $rs->name.'.xlsx');
        }else{
            return new HttpException(400, '无数据');
        }

    }

}
