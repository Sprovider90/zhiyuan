<?php

namespace App\Http\Controllers\Api;

use App\Models\Breakdown;
use Illuminate\Http\Request;
use App\Http\Resources\BreakdownResource;

use Spatie\QueryBuilder\QueryBuilder;




class BreakdownController extends Controller
{
	public function index(Request $request,Breakdown $beackdown)
    {
    	$query = Breakdown::class;
        if(isset($request->project_id)&&!empty($request->project_id)){
        	$query =$beackdown->where('project_id',$request['project_id']);
        }
        if(isset($request->device_id)&&!empty($request->device_id)){
            $query =$beackdown->where('device_id',$request['device_id']);
        }
        $beackdowns = QueryBuilder::for($query)
            ->allowedIncludes('project')
            ->paginate();
        return BreakdownResource::collection($beackdowns);
    }
    

}
