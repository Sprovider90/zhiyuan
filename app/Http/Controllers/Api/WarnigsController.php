<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarnigsResource;
use App\Models\Warnigs;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class WarnigsController extends Controller
{
    public function index(Request $request,Warnigs $Warnigs)
    {

        $query = Warnigs::class;
//        if(isset($request->type)&&!empty($request->type)){
//            $query =$Warnigs->where('type',$request['type']);
//        }

        $Warnigs = QueryBuilder::for($query)
            ->allowedIncludes('project','projectsPositions')
            ->paginate();
        return WarnigsResource::collection($Warnigs);
    }
}
