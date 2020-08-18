<?php

namespace App\Http\Controllers\Api;

use App\Models\Dictories;
use Illuminate\Http\Request;
use App\Http\Resources\DictoriesResource;
use Spatie\QueryBuilder\QueryBuilder;
class DictoriesController extends Controller
{

	public function index(Request $request)
    {
        $query = Dictories::class;
        $dictories = QueryBuilder::for($query)
            ->paginate($request->pageSize ?? $request->pageSize);
        return DictoriesResource::collection($dictories);
    }

//    public function update(UserRequest $request,User $user)
//    {
//       	\DB::beginTransaction();
//       	try{
//	        $attributes = $request->only(['truename', 'password','status','img']);
//
//	        if (isset($request->roles)) {
//
//	        	$roles=explode(',', $request->roles);
//	        	$r_all = Role::all();
//		        foreach ($r_all as $p) {
//		            $user->removeRole($p);
//		        }
//	            foreach ($roles as $role) {
//	                $role_r = Role::where('id', '=', $role)->firstOrFail();
//	                $user->assignRole($role_r); //Assigning role to user
//	            }
//
//	        }
//	        $user->update($attributes);
//	        \DB::commit();
//        }catch(\Exception $e){
//    		\DB::rollBack();
//    		abort(400, '内部错误');
//    	}
//        return new UserResource($user);
//    }


}
