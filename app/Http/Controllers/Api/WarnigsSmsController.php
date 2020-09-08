<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\WarnigsSmsResource;
use App\Http\Requests\Api\WarnigsSmsRequest;
use App\Models\WarnigsSms;
use App\Models\Warnigs;
use App\Models\Files;
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

        $WarnigsSms = $query->with("user")->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);

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
        $warnigssms->fill($request->all());
        $warnigssms->warnig_id=$warnigs->id;
        $warnigssms->send_id=$request->user()->id;
        $warnigssms->customer_id=$request->user()->customer_id;
        $warnigssms->save();

        return new WarnigsSmsResource($warnigssms);
    }

    public function update(Warnigs $warnigs,WarnigsSms $warnigssms,WarnigsSmsRequest $request)
    {
        $warnigssms->update($request->all());
        return new WarnigsSmsResource($warnigssms);
    }
}
