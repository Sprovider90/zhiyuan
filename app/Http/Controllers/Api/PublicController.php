<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilesRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\FilesResource;
use App\Http\Resources\OrdersResources;
use App\Http\Resources\ProjectsAreasResource;
use App\Http\Resources\ThresholdsResource;
use App\Models\Customers;
use App\Models\Device;
use App\Models\Files;
use App\Models\ProjectsAreas;
use App\Models\Thresholds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublicController extends Controller
{
    //获取所有 状态 正常  已停止 没有绑定客户的列表
    public function getAllNoCustomerDevicesList(Request $request,Device $device){
        return new DeviceResource($device->whereNull('customer_id')->where('status',1)->orderBy('id','desc')->get());
    }

    //对应项目设备列表
    public function devices(Request $request,Device $device){
        $customer_id = $request->get('customer_id','');
        $customer_id && $device = $device->where('customer_id',$customer_id);
        return new OrdersResources($device->where('status',1)->orderBy('id','desc')->get());
    }

    //对应项目区域列表
    public function areas(Request $request,ProjectsAreas $projectsAreas){
        $project_id = $request->get('project_id','');
        $project_id && $projectsAreas = $projectsAreas->where('project_id',$project_id);
        return new ProjectsAreasResource($projectsAreas->orderBy('id','desc')->get());
    }

    //监测标准列表
    public function thresholds(Thresholds $thresholds){
        return new ThresholdsResource($thresholds->where('status',1)->orderBy('id','desc')->get(['id','name','status','thresholdinfo']));
    }

    //客户列表
    public function customers(Customers $customers){
        return new CustomersResources($customers->orderBy('id','desc')->get(['id','company_name','company_addr']));
    }


    // 图片上传
    public function store(FilesRequest $request){
        $file = $request->file('file');
        $type = $request->get('type',0); //0图片 1视频

        $fileSize = sprintf("%.2f",round($file->getSize()/1024,2));
        $fileExt = strtolower($file->getClientOriginalExtension());

        switch ($type){
            case 0:
                //校验图片格式 图片大小
                $configFileMaxSize = config('filesystems.UPLOAD_IMAGE_MAX_SIZE');
                $configFileExt = config('filesystems.UPLOAD_IMAGE_EXT');
                break;
            case 1:
                //校验视频格式 视频大小
                $configFileMaxSize = config('filesystems.UPLOAD_VIDEOS_MAX_SIZE');
                $configFileExt = config('filesystems.UPLOAD_VIDEOS_EXT');
                break;
        }

        if(round($fileSize/1024/1024,2) > $configFileMaxSize){
            throw new HttpException(403, '文件最大不超过'.$configFileMaxSize.'M');
        }
        if(!in_array(strtolower($fileExt),explode(',',$configFileExt))){
            throw new HttpException(403, '文件不在允许的'.$configFileExt.'扩展中');
        }
        $clientName = $file->getClientOriginalName();
        $fileTmp = $file->getRealPath();
        $fileName = date("YmdHis").rand(10000000,99999999).'.'.$fileExt;
        $fileMime = $file->getMimeType();
        $fileUpFlg = Storage::disk('public')->put($fileName,file_get_contents($fileTmp));
        $filePath = Storage::disk('public')->url($fileName);
        if($fileUpFlg){
            #插入数据库
            $file = Files::create([
                'name'          =>  $fileName,
                'size'          =>  $fileSize,
                'ext'           =>  $fileExt,
                'path'          =>  $filePath,
                'mime'          =>  $fileMime,
                'upload_name'   =>  $clientName]);
            return response(new FilesResource($file),201);

        }else{
            return new HttpException(400, '内部错误');
        }

    }
}
