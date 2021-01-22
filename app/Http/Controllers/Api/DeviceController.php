<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\StorehousesResource;
use App\Imports\DeviceImport;
use App\Models\Device;
use App\Models\ProjectsPositions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redis;

class DeviceController extends Controller
{
    //
    public function count(Request $request){
        //增加客户登录
        $where = [];
        $request->user()->customer_id && $where[] = ['customer_id',$request->user()->customer_id];
        if($request->type && in_array($request->type,[1,2,3])){
            $date = $this->returnDate($request->type);
            $where[] = ['created_at','>',$date[0]];
            $where[] = ['created_at','<',$date[1]];
        }
        //设备总数
        $all_count = Device::where($where)->count();
        //在库设备数
        $in_count = Device::where($where)->where('store_status',1)->count();
        //已出库
        $out_count = Device::where($where)->where('store_status',2)->count();
        //运行中设备
        $run_count = Device::where($where)->where('run_status',1)->count();

        return response()->json([
            'all'       => $all_count,
            'in_count'  => $in_count,
            'out_count' => $out_count,
            'run_count' => $run_count,
        ]);
    }

    public function index(Request $request,Device $device){
        $device = $device->with(['storehouse','customer','customer.user:id,customer_id,truename,name']);
        //增加客户登录
        $request->user()->customer_id && $device = $device->where('customer_id',$request->user()->customer_id);
        $request->device_number &&   $device = $device->where('device_number','like',"%{$request->device_number}%");
        $request->run_status &&   $device = $device->where('run_status',$request->run_status);
        $request->status && $device = $device->where('status',$request->status);
        $request->store_status && $device = $device->where('store_status',$request->store_status);
        $request->type && $device = $device->where('type',$request->type);
        $device = $device->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);
        $device->each(function ($v){
            //设备是否绑定监测点 1启用 2停用 3未绑定
            $flg = 3;
            $bangind = ProjectsPositions::where('device_id',$v->id)->where('status',1)->count();
            if($bangind){
                $flg = 1;
            }else{
                $bangind1 = ProjectsPositions::where('device_id',$v->id)->where('status',2)->count();
                if($bangind1){
                    $flg = 2;
                }
            }
            $v['position_flg'] =  $flg;
        });
        return response(new DeviceResource($device));
    }

    public function store(DeviceRequest $request,Device $device){
        $data = $request->all();
        $data['store_status'] = $request->customer_id ? 2 : 1 ;
        $device = $device->create($data);
        return response(new DeviceResource($device),201);
    }

    public function update(Device $device,DeviceRequest $request){
        $data = $request->all();
        $data['store_status'] = $request->customer_id ? 2 : 1 ;
        $device->update($data);
        return response(new DeviceResource($device),201);
    }

    public function show(Device $device){
        $device = $device->load(['storehouse','customer']);
        return new DeviceResource($device);
    }

    public function cancel(Device $device){
        $device->update(['customer_id' => null,'store_status' => 1,'run_status' => 2, 'type' => 2]);
        return response(new DeviceResource($device),201);
    }


    public function import(Request $request,Excel $excel){
        $file = $request->file('file');
        if(!$file){
            return response()->json(['message' => '请选择要导入的Excel文件'],404);
        }
        $data = Excel::toArray(new DeviceImport(), $file);
        unset($data[0][0]);
        try{
            DB::beginTransaction();
            foreach ($data[0] as $row) {
                $number_flg = Device::where('device_number',$row[0])->count();
                if($number_flg){
                    DB::rollBack();
                    return response()->json(['message' => '插入失败,设备ID:'.$row[0].'已存在'],403);
                }
                $model = new Device();
                $flg = $model->create(
                    $data[] = [
                        'good_id'           => 1,
                        'device_number'     => $row[0],
                        'come_date'         => date("Y-m-d",@intval(($row[1] - 25569) * 3600 * 24)),
                        'model'             => $row[2],
                        'manufacturer'      => $row[3],
                        'storehouse_id'     => $row[4],
                        'customer_id'       => $row[5],
                        'check_data'        => $row[6],
                        'status'            => $row[7],
                        'type'              => $row[8],//1销售 2租赁
                    ]
                );
                if(!$flg){
                    DB::rollBack();
                    return $this->errorResponse(500,'系统错误');
                }
            }
            DB::commit();
            return response('',201);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse(500,$e->getMessage());
        }
    }

    public function state(Device $device,Request $request){
        if($request->state!=1 && $request->state !=2){
            return $this->errorResponse(403,'参数错误');
        }
        if($request->state != $device->state){
            $device->update(['state' => $request->state]);
        }

        $arr=[];
        $arr["event"]=intval($request->state);
        $arr["timestamp"]=date('Y-m-d H:i:s',time());
        $arr["deviceId"]=$device->id;

        Redis::rpush('phpsay',json_encode($arr));

        return $device;
    }
}
