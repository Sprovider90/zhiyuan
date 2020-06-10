<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorehousesRequest;
use App\Http\Resources\StorehousesResource;
use App\Models\Storehouses;
use Illuminate\Http\Request;

class StorehousesController extends Controller
{
    //
    public function index(Storehouses $storehouses, Request $request)
    {
        if($request->name){
            $storehouses = $storehouses->where('name', 'like', '%' . $request->name. '%');
        }
        return new StorehousesResource($storehouses->paginate(10));
    }

    // 仓库新增
    public function store(StorehousesRequest $request){
        $storehouses = Storehouses::create($request->all());
        return new StorehousesResource($storehouses);
    }
    public function edit(Storehouses $storehouse)
    {
        return new StorehousesResource($storehouse);
    }

    public function update(Storehouses $storehouse, StorehousesRequest $request)
    {
        $storehouse->update($request->all());
        return new StorehousesResource($storehouse);
    }
    public function destroy($id)
    {
        Storehouses::where('id',$id)->delete();
        return response(null, 204);
    }
}
