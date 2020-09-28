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
use App\Models\Files;
use App\Models\Projects;
use App\Models\ProjectsAreas;
use App\Models\ProjectsPositions;
use App\Models\ProjectsStages;
use App\Models\ProThresholdsLog;
use Excel;
use App\Exports\PositiondatasExport;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exports\PositiondatasTenExport;

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

        if($request->type==2){
            $params["img"]=$request->img?$request->img:"";
            return $this->export_ten($params);
        }

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
            //$rs=ProjectsPositions::where('id', $arr["body"]["list"][0]["monitorId"])->first();
            return Excel::download($export, '1.xlsx');
        }else{
            return new HttpException(400, '无数据');
        }

    }
    public function export_ten($data)
    {
        if(empty($data["img"])){
            abort(422, "图片必传");
        }
        $imgs=Files::where('id', $data["img"])->first();

        if(!$imgs->path){
            abort(422, "图片不存在");
        }
        $url=config("javasource.original.url");

        $result = Common::curl($url, $data, false);
        $arr=json_decode($result,true);
        if(!empty($arr["body"]["list"])){
            $export_data=[];
            $export_data[]=['统计时间','甲醛（mg/m3）','TVOC（mg/m3）','PM2.5（μg/m3）','CO2（ppm）','温度（℃）','湿度（%RH）','项目大阶段','项目小阶段'];
            foreach ($arr["body"]["list"] as $k=>$v){
                if(isset($v["stageId"]) && isset($v["status"])){
                    list($big,$small)=$this->turnData($v["stageId"],$v["status"]);
                }else{
                    $big="无";
                    $small="无";
                }

                $export_data[]=[$v["timestamp"],$v["formaldehyde"],$v["TVOC"],$v["PM25"],$v["CO2"],$v["temperature"],$v["humidity"],$big,$small];
            }

            $export = new PositiondatasTenExport($export_data,$imgs->path);

            return Excel::download($export, '10.xlsx');
        }else{
            return new HttpException(400, '无数据');
        }
    }
    protected function turnData($stage_id,$status){
        $result=["",""];
        //大小阶段转换阶段分类 1施工阶段 2交付阶段 3维护阶段
        //状态0未开始1暂停中2已结束3项目错误4施工中5交付中6维护中7项目大阶段错误
        if(!in_array($status,[4,5,6])){
            $result=["暂停","暂停"];
        }else{
            $rs=ProjectsStages::where('id',$stage_id)->first();
            if(!empty($rs)){
                switch ($rs->stage)
                {
                    case 1:
                        $result=["施工阶段",$rs->stage_name];
                        break;
                    case 2:
                        $result=["交付阶段",$rs->stage_name];
                        break;
                    case 3:
                        $result=["维护阶段",$rs->stage_name];
                        break;
                    default:

                }
            }
        }
        return $result;
    }
}
