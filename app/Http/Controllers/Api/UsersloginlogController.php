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
        $query=Loginlog::class;
        if(isset($request->reuse_param)&&!empty($request->reuse_param)){
            $query =$Loginlog->where('users_name','like','%'.$request['reuse_param'].'%')
                ->orWhere('users_truename','like','%'.$request['reuse_param'].'%');

        }

        $Loginlog = QueryBuilder::for($query)
            ->allowedIncludes('users')->orderBy('id','desc')
            ->paginate($request->pageSize ?? $request->pageSize);
        return UsersloginlogResources::collection($Loginlog);
    }


}
