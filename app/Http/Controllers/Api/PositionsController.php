<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PositionsRequest;
use App\Http\Resources\OrdersResources;
use App\Http\Resources\PositionsResource;
use App\Jobs\UpdateDevicesInfoJob;
use App\Models\Device;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionsController extends Controller
{
    //
    public function store(PositionsRequest $request,Position $positions,Device $device)
    {
        $positions = $positions->create($request->all());
        $positions->location()->create($request->all());
        //同时修改设备：运行中
        $device->where('id',$positions->device_id)->update(['run_status' => 1]);

        $request->device_id && dispatch(new UpdateDevicesInfoJob(["device_id"=>$request->device_id]));
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
        //原设备 已停止状态
        $device->where('id',$position->device_id)->update(['run_status' => 2]);
        $position->update($request->all());
        $position->location()->delete();
        $position->location()->create($request->all());
        //同时修改设备 运行中状态
        $device->where('id',$request->device_id)->update(['run_status' => 1]);
        $request->device_id && dispatch(new UpdateDevicesInfoJob(["device_id"=>$request->device_id]));
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
        $position->update(['status' => $request->status]);
        //同时修改设备 已停止状态
        $device->where('id',$position->device_id)->update(['run_status' =>  $request->status == 1 ? 1 : 2]);
        return response(new PositionsResource($position),201);
    }

}
