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
}
