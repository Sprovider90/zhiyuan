<?php

namespace App\Http\Controllers\Api;

use App\Models\Loginlog;
use Illuminate\Http\Request;
use App\Http\Resources\UsersloginlogResources;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class UsersloginlogController extends Controller
{
	public function index(Request $request,Loginlog $Loginlog)
    {
//        if(isset($request->reuse_param)&&!empty($request->reuse_param)){
//            $query =$user->where('name','like','%'.$request['reuse_param'].'%')
//                ->orWhere('truename','like','%'.$request['reuse_param'].'%')
//                ->orWhere('phone','like','%'.$request['reuse_param'].'%')
//                ->orWhere('id',$request['reuse_param']);
//        }

        $Loginlog = QueryBuilder::for(Loginlog::class)
            ->allowedIncludes('users')
            ->paginate($request->pageSize ?? $request->pageSize);
        return UsersloginlogResources::collection($Loginlog);
    }


}
