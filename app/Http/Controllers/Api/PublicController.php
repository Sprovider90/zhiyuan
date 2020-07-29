<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilesRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\FilesResource;
use App\Models\Customers;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublicController extends Controller
{
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
            throw new HttpException(401, '文件最大不超过'.$configFileMaxSize.'M');
        }

        if(!in_array($fileExt,explode(',',$configFileExt))){
            throw new HttpException(401, '文件不在允许的'.$configFileExt.'扩展中');
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