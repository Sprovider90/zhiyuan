<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    public function errorResponse($statusCode, $message=null, $code=0)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}
