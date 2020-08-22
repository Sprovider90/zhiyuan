<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarnigsResource;
use App\Models\Warnigs;
use Illuminate\Http\Request;
use App\Models\WarnigsSms;

class WarnigsController extends Controller
{
    public function index(Request $request,Warnigs $Warnigs)
    {
        $data = $Warnigs->with(['project','project.customs','projectsPositions','projectsPositions.area'])->paginate();
        foreach ($data as $k => $v){
            $v->smscount = WarnigsSms::where('warnig_id',$v->id)->count();
            $v->isnew=1;
        }
        return new WarnigsResource($data);
    }
    public function show(Request $request,Warnigs $warnig)
    {

        $data = $warnig->load(['project','project.customs']);
        $data["originaldata_name"]="";
        if(isset($data["originaldata"]) && $originaldata_arr=json_decode($data["originaldata"])){
            $data["originaldata_name"]=$originaldata_arr["name"];
        }

        return new WarnigsResource($data);
    }
}
