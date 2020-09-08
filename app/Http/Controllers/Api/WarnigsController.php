<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarnigsResource;
use App\Models\Projects;
use App\Models\Warnigs;
use Illuminate\Http\Request;
use App\Models\WarnigsSms;

class WarnigsController extends Controller
{
    public function index(Request $request,Warnigs $Warnigs)
    {
        if($request->user()->customer_id){
            $projects=Projects::where("customer_id",$request->user()->customer_id)->pluck("id")->toArray();
            if(!empty($projects)){
                $Warnigs = $Warnigs->whereIn('project_id',$projects);
            }else{
                $Warnigs = $Warnigs->whereIn('project_id',[]);
            }

        }

        $Warnigs = $Warnigs->where('threshold_keys', '!=' , "");

        $request->time      && $Warnigs = $Warnigs->whereDate('created_at',$request->time);

        $request->threshold_keys      && $Warnigs = $Warnigs->where('threshold_keys','like',"%{$request->threshold_keys},%");

        $request->reuseparam      && $Warnigs = $Warnigs->whereHas('project',function($query) use ($request){
            $query->where('name','like',"%{$request->reuseparam}%");
        });

        $data = $Warnigs->with(['project','project.customs','projectsPositions'])->with(['projectsPositions.area'=>function($query){
            $query->withTrashed();}])->orderBy('id','desc')->paginate();


        $updatefeid="nocustomerred";
        if($request->user()->customer_id){
            $updatefeid="customerred";
        }


        foreach ($data as $k => $v){
            $v->smscount = WarnigsSms::where('warnig_id',$v->id)->count();

            $fis=WarnigsSms::where(['warnig_id'=>$v->id,$updatefeid=>0])->first(["id"]);

            $v->isnew=isset($fis->id)?1:0;
        }
        return new WarnigsResource($data);
    }
    public function show(Request $request,Warnigs $warnig)
    {

        $data = $warnig->load(['project','project.customs']);
        $data["originaldata_name"]="";
        if(isset($data["originaldata"]) && $originaldata_arr=json_decode($data["originaldata"])){
            $data["originaldata_name"]=isset($originaldata_arr["name"])?$originaldata_arr["name"]:"";
        }

        return new WarnigsResource($data);
    }
}
