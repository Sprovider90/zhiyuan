<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\WarnigsSmsResource;
use App\Http\Requests\Api\WarnigsSmsRequest;
use App\Models\WarnigsSms;
use App\Models\Warnigs;
use App\Models\Files;
use Illuminate\Support\Facades\Redis;

class WarnigsSmsController extends Controller
{
    public function index(WarnigsSmsRequest $request, WarnigsSms $WarnigsSms)
    {
        $query = $WarnigsSms->query();
        //设置数据已读
        $updatefeid="nocustomerred";
        if($request->user()->customer_id){
            $updatefeid="customerred";
        }
        $wherered=["warnig_id"=>$request->warnig_id,$updatefeid=>0];
        WarnigsSms::where($wherered)->update([$updatefeid=>1]);
        //end
        //if($request->user()->customer_id)
        if ($warnig_id = $request->warnig_id) {
            $query->where('warnig_id', $warnig_id);
        }

        $WarnigsSms = $query->with(["user","user.img"])->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);

        foreach ($WarnigsSms as $k => $v){
            $v->pics_img ==[];
            if(isset($v->pics) && !empty($v->pics)){
                $v->pics_img = Files::whereIn('id',explode(",",$v->pics))->get();
            }
        }
        return WarnigsSmsResource::collection($WarnigsSms);
    }
    public function store(WarnigsSmsRequest $request,Warnigs $warnigs,WarnigsSms $warnigssms)
    {
        if($request->user()->customer_id!=0 && (!isset($request->pics) or empty($request->pics))){
            abort(422, "图片必传");
        }
        $warnigssms->fill($request->all());
        $warnigssms->warnig_id=$warnigs->id;
        $warnigssms->send_id=$request->user()->id;
        $warnigssms->customer_id=$request->user()->customer_id;
        $warnigssms->save();
        $arr=[];
        $arr["stage"]=1004;
        if($request->user()->customer_id==0){
            $arr["stage"]=1005;
        }
        $arr["time"]=date('Y-m-d H:i:s',time());
        $arr["warnig_id"]=$warnigssms->warnig_id;
        Redis::rpush('messagelist',json_encode($arr));

        return new WarnigsSmsResource($warnigssms);
    }

    public function update(Warnigs $warnigs,WarnigsSms $warnigssms,WarnigsSmsRequest $request)
    {
        $warnigssms->update($request->all());
        return new WarnigsSmsResource($warnigssms);
    }
    public function lastsms(Warnigs $warnigs,WarnigsSms $WarnigsSms)
    {
        $query = $WarnigsSms->query();
        $query->where('customer_id', 0);
        $query->where('warnig_id', $warnigs->id);

        $warnigssms=$query->with(["user","user.img"])->orderBy('id','desc')->first();

        if(isset($warnigssms->pics) && !empty($warnigssms->pics)){
            $warnigssms->pics_img = Files::whereIn('id',explode(",",$warnigssms->pics))->get();
        }

        return new WarnigsSmsResource($warnigssms);
    }

}
