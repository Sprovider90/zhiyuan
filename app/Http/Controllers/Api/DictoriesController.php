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
        $values=json_decode($attributes["value"],true);
        $give_arr=array_column($values,"id");
        $differ=array_diff($sql_arr,$give_arr);
        if(!empty($differ)){
            abort(422, 'value里面的值不能删除');
        }
        /*
         * 组装id
         * */

        $max_id=1;
        if(!empty($sql_arr)){
            rsort($sql_arr);
            $max_id=$sql_arr[0]+1;
        }
        foreach ($values as $k =>&$v){
            if(!isset($v["id"])){
                $v["id"]=$max_id;
                $max_id++;
            }
        }
        $attributes["value"]=json_encode($values);
       	try{
            $dictories->update($attributes);
        }catch(\Exception $e){
    		abort(400, $e->getMessage());
    	}
        return new DictoriesResource($dictories);
    }


}
