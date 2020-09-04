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
use Excel;
use App\Exports\BaseExport;
use Symfony\Component\HttpKernel\Exception\HttpException;
class PositiondatasController extends Controller
{
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
