<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorehousesRequest;
use App\Http\Resources\StorehousesResource;
use App\Models\Storehouses;
use Illuminate\Http\Request;

class StorehousesController extends Controller
{
    // 仓库列表
    public function index(Storehouses $storehouses, Request $request)
    {
        if($request->name){
            $storehouses = $storehouses->where('name', 'like', '%' . $request->name. '%');
        }
        return new StorehousesResource($storehouses->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize));
    }

    // 仓库新增
    public function store(StorehousesRequest $request){
        $storehouses = Storehouses::create($request->all());
        return response(new StorehousesResource($storehouses),201);
    }

    // 仓库编辑
    public function edit(Storehouses $storehouse)
    {
        return new StorehousesResource($storehouse);
    }

    // 仓库更新
    public function update(Storehouses $storehouse, StorehousesRequest $request)
    {
        $storehouse->update($request->all());
        return response(new StorehousesResource($storehouse),201);
    }

    // 仓库删除
    public function destroy($id)
    {
        Storehouses::where('id',$id)->delete();
        return response(null, 204);
    }
}
