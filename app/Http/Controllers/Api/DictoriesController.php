<?php

namespace App\Http\Controllers\Api;

use App\Models\Dictories;
use Illuminate\Http\Request;
use App\Http\Resources\DictoriesResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\DictoriesRequest;
class DictoriesController extends Controller
{

	public function index(Request $request)
    {
        $query = Dictories::class;
        $dictories = QueryBuilder::for($query)
            ->paginate($request->pageSize ?? $request->pageSize);
        return DictoriesResource::collection($dictories);
    }

    public function update(DictoriesRequest $request,$id)
    {
        $dictories = Dictories::findOrFail($id);
        $sql_arr=array_column(json_decode($dictories["value"]),"id");

        $attributes = $request->only(['value']);
        //$attributes["id"]=$id;
        $give_arr=array_column(json_decode($attributes["value"]),"id");
        $differ=array_diff($sql_arr,$give_arr);
        if(!empty($differ)){
            abort(422, 'value里面的值不能删除');
        }
       	try{
            $dictories->update($attributes);
        }catch(\Exception $e){
    		abort(400, '内部错误');
    	}
        return new DictoriesResource($dictories);
    }


}
