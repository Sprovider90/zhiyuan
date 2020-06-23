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
        $Loginlog = QueryBuilder::for(Loginlog::class)
            ->allowedIncludes('users')
            ->paginate();
        return UsersloginlogResources::collection($Loginlog);
    }
    
    
}
