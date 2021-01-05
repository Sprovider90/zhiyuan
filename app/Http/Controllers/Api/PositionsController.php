<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PositionsRequest;
use App\Http\Resources\OrdersResources;
use App\Http\Resources\PositionsResource;
use App\Jobs\UpdateDevicesInfoJob;
use App\Models\Device;
use App\Models\Location;
use App\Models\Position;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PositionsController extends Controller
{
    //
    public function store(PositionsRequest $request,Position $positions,Device $device)
    {
        $positions = $positions->create($request->all());
        $positions->location()->create($request->all());
        //同时修改设备：运行中
        $device->where('id',$positions->device_id)->update(['run_status' => 1]);
        $request->device_id && dispatch(new UpdateDevicesInfoJob(["device_id"=>$request->device_id,'fromwhere'=>'PositionsController_store']));
        return response(new PositionsResource($positions->load('location')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionsRequest $request,Position $position,Device $device)
    {
        //校验区域是否改变
        if($position->area_id  != $request->area_id){
            if(!isset($location->left) && !isset($request->top)){
                throw new HttpException(403, '请修改点位坐标');
            }
            //查询坐标
            $location = Location::where('position_id',$position->id)->first();
            if($location){
                if($location->left == $request->left && $location->top == $request->top){
                    throw new HttpException(403, '请修改点位坐标');
                }
            }
        }
        //原设备 已停止状态
        $device->where('id',$position->device_id)->update(['run_status' => 2]);
//        dispatch(new UpdateDevicesInfoJob(["device_id"=>$position->device_id,'fromwhere' => 'PositionsController_update']));
        $position->update($request->all());
        $position->location()->delete();
        $position->location()->create($request->all());
        //同时修改设备 运行中状态
        $device->where('id',$request->device_id)->update(['run_status' => 1]);
        $request->device_id && dispatch(new UpdateDevicesInfoJob(["device_id"=>$request->device_id,'fromwhere' => 'PositionsController_update'])) ;

        return response(new PositionsResource($position->load('location')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        Position::where('id',$id)->delete();
        return response(null, 204);
    }*/

    public function status(Request $request,Position $position,Device $device){
        //查询是否已经在运行
        $device1 = Device::where('id',$position->device_id)->first();
        if($device1->run_status==1 && $request->status == 1){
            throw new HttpException(403, '设备在其他点位运行,请勿重复添加');
        }
        $project = Projects::find($position->project_id);
        if(($project->customer_id  !=  $device1->customer_id ) && $request->status == 1){
            throw new HttpException(403, '设备所属客户不一致');
        }
        $position->update(['status' => $request->status]);
        //同时修改设备 已停止状态
        $device->where('id',$position->device_id)->update(['run_status' =>  $request->status == 1 ? 1 : 2]);
        dispatch(new UpdateDevicesInfoJob(["device_id"=>$position->device_id,'fromwhere' => 'PositionsController_status']));

        return response(new PositionsResource($position),201);
    }

}
