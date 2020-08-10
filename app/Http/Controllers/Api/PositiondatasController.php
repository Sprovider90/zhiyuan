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

class PositiondatasController extends Controller
{
    public function index(PositiondatasRequest $request)
    {
        $params=[];
        $params["currPage"]=$request->currPage?$request->currPage:1;
        $params["pageSize"]=$request->pageSize?$request->pageSize:3;
        $params["startTime"]=$request->startTime;
        $params["endTime"]=$request->endTime;
        $params["monitorId"]=$request->monitorId;
        $url=config("javasource.original.url");
        $result = Common::curl($url, $params, false);
        return $result;
    }
}