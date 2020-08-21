<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\StorehousesResource;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    //
    public function count(Request $request,Device $device){
        //增加客户登录
        $request->user()->customer_id && $device = $device->where('customer_id',$request->user()->customer_id);
        $date = $this->returnDate($request->type ?? 2);
        //设备总数
        $all_count = $device->whereBetween('created_at',$date)->count();
        //在库设备数
        $in_count = $device->whereBetween('created_at',$date)->where('store_status',1)->count();
        //已出库
        $out_count = $device->whereBetween('created_at',$date)->where('store_status',2)->count();
        //运行中设备
        $run_count = $device->whereBetween('created_at',$date)->where('run_status',1)->count();

        return response()->json([
            'all'       => $all_count,
            'in_count'  => $in_count,
            'out_count' => $out_count,
            'run_count' => $run_count,
        ]);
    }

    public function index(Request $request,Device $device){
        $device = $device->with(['storehouse','customer']);
        //增加客户登录
        $request->user()->customer_id && $device = $device->where('customer_id',$request->user()->customer_id);
        $request->device_number &&   $device->where('device_number','like',"%{$request->device_number}%");
        $request->run_count &&   $device->where('status',$request->run_count);
        $request->store_status && $device->where('store_status',$request->store_status);
        $device = $device->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);
        return response(new DeviceResource($device));
    }

    public function store(DeviceRequest $request,Device $device){
        $device = $device->create($request->all());
        return response(new DeviceResource($device),201);
    }

    public function update(Device $device,DeviceRequest $request){
        $device->update($request->all());
        return response(new DeviceResource($device),201);
    }

    public function show(Device $device){
        $device = $device->load(['storehouse','customer']);
        return new DeviceResource($device);
    }

    public function cancel(Device $device){
        $device->update(['customer_id' => null]);
        return response(new DeviceResource($device),201);
    }

}
