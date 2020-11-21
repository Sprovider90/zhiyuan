<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionsResource;
use App\Http\Resources\ProjectsResources;
use App\Models\ProjectsPositions;
use App\Models\Tag;
use Illuminate\Http\Request;

class DataController extends Controller
{
    //
    public function index(Request $request,ProjectsPositions $position){
        $position = $position->with(['project','area','device']);
        $request->user()->customer_id && $position->whereHas('project',function ($query) use ($request){
            $query->where('customer_id',$request->user()->customer_id);
        });
        /*$request->keyword && $position
            ->where(function ($query) use ($request) {
                $query->orWhere('name','like',"%{$request->keyword}%")
                      ->orWhereHas('project',function ($query) use ($request) {
                          $query->where('name','like',"%{$request->keyword}%");
                      });
            });*/
        //项目名称
        $request->project_name &&  $position = $position->WhereHas('project',function ($query) use ($request) {
            $query->where('name','like',"%{$request->project_name}%");
        });
        //点位名称
        $request->position_name &&  $position = $position->where('name','like',"%{$request->position_name}%");
        //电量
        $request->soc && $position = $position->whereHas('device' , function($query) use ($request) {
            $query->where('soc','<',$request->soc);
        });
        //电量
        $request->device_number && $position =  $position->whereHas('device' , function($query) use ($request) {
            $query->where('device_number','like',"%{$request->device_number}%");
        });
        $position = $position->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);
        foreach ($position as $k => $v){
            $tag = Tag::where('model_type',3)->where('model_id',$v->id)->orderBy('id','desc')->first();
            if($tag){
                $v->tag = $tag->air_quality;
            }else{
                $v->tag = '';
            }
            $v->device->run_status = $v->status;
        }
        return response(new PositionsResource($position));
    }
}
