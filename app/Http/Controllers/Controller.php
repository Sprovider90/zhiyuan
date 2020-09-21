<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    public function errorResponse($statusCode, $message=null, $code=0)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    function returnMonthRange($start, $end)
    {
        $end = date('Ym', strtotime($end)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $month = date('Ym', strtotime($start . ' + ' . $i . ' month'));
            $range[] = ['date' => date('Y-m', strtotime($start . ' + ' . $i . ' month'))];
            $i++;
        } while ($month < $end);

        return $range;
    }

    public function getProjectThreshold($project_id){
            $data = DB::select('SELECT
                    a.id AS project_id,
                    a.stage_id,
                    c.`name` AS thresholds_name,
                    CASE
                WHEN d.thresholdinfo IS NULL THEN
                    "thresholds"
                ELSE
                    "projects_thresholds"
                END AS fromwhere,
                 CASE
                WHEN d.thresholdinfo IS NULL THEN
                    c.thresholdinfo
                ELSE
                    d.thresholdinfo
                END AS thresholdinfo
                FROM
                    `projects` a
                LEFT JOIN projects_stages b ON a.stage_id = b.id
                LEFT JOIN thresholds c ON b.threshold_id = c.id
                LEFT JOIN projects_thresholds d ON a.stage_id = d.stage_id
                WHERE
                    a. STATUS IN (4, 5, 6)
                AND a.stage_id IS NOT NULL
                AND c.thresholdinfo IS NOT NULL
                AND a.id='.$project_id);
            return $data ? $data[0] : '';
    }

    public function getChinaName($string){
        $new_string = '';
        $arr = ["humidity"=>"湿度","temperature"=>"温度","formaldehyde"=>"甲醛","PM25"=>"PM25","CO2"=>"CO2","TVOC"=>"TVOC"];
        $data = explode(',',$string);
        foreach ($data as $k => $v){
            $new_string .= $arr[$v] .',';
        }
        return mb_substr($new_string,0,-1);
    }

    /***
     * 返回开始结束日期  统一函数
     *
     * @param int $type 1 本周 2 本月 3本年度
     * @return array 【开始日期,结束日期】
     */
    public function returnDate($type = 1){
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

    /**
     * 返回两个日期中所有日期
     * @param $start_date
     * @param $end_date
     * @return array
     */
    function returnDateList($start_date,$end_date){
        $arr = [];
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);
        if($start_date > $end_date) return [];
        if($end_date >  time()){
            $end_date = date("Y-m-d");
        }
        while ($end_date >= $start_date) {
            $arr[] = array('date' =>  date('Y-m-d', $start_date));
            $start_date = strtotime('+1 day', $start_date);
        }
        return $arr;
    }

    /**
     * 返回两个日期所有月
     * @param $start_date
     * @param $end_date
     * @return array
     */
    function returnMonthList($start_date,$end_date){
        $monarr = array();
        $monarr[] = ['date' => $start_date];
        $start_date	= strtotime($start_date);
        $end_date 	= strtotime($end_date);
        while( ($start_date =  strtotime('+1 month', $start_date)) <= $end_date){
            $monarr[] = array('date' =>  date('Y-m', $start_date));
        }
        return $monarr;
    }
}
