<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\Api\UsercheckRequest;
use Spatie\Permission\Models\Role;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;



class UsersController extends Controller
{
    public function me(Request $request)
    {
        return (new UserResource($request->user()->with(["img"])->first()));
    }

	public function index(UserRequest $request,User $user)
    {
    	$query = User::class;
        if(isset($request->reuse_param)&&!empty($request->reuse_param)){
        	$query =$user->where('name','like','%'.$request['reuse_param'].'%')
                    ->orWhere('truename','like','%'.$request['reuse_param'].'%')
                    ->orWhere('phone','like','%'.$request['reuse_param'].'%')
                    ->orWhere('id',$request['reuse_param']);
         }

    	//\DB::connection()->enableQueryLog();
    	$usrs = QueryBuilder::for($query)
            ->allowedIncludes('customer','roles')
            ->paginate();
            //var_dump(\DB::getQueryLog());exit;
        return UserResource::collection($usrs);
    }
    public function check(UsercheckRequest $request)
    {
        return response(null, 200);
    }
    public function store(UserRequest $request)
    {
    	\DB::beginTransaction();
    	try{
	    	$user = User::create($request->only('phone', 'name', 'password', 'truename', 'type', 'customer_id'));
	        $roles = $request['roles']; // 获取输入的角色字段
	        // 检查是否某个角色被选中

	        if (isset($roles)) {
	        	$roles=explode(',', $roles);
	            foreach ($roles as $role) {
	                $role_r = Role::where('id', '=', $role)->firstOrFail();
	                $user->assignRole($role_r); //Assigning role to user
	            }
	        }
	        \DB::commit();
        }catch(\Exception $e){
    		\DB::rollBack();
    		abort(400, $e->getMessage());
    	}

        return new UserResource($user);
    }
    public function update(UserRequest $request,User $user)
    {
       	\DB::beginTransaction();
       	try{
	        $attributes = $request->only(['truename', 'password','status','img']);

	        if (isset($request->roles)) {

	        	$roles=explode(',', $request->roles);
	        	$r_all = Role::all();
		        foreach ($r_all as $p) {
		            $user->removeRole($p);
		        }
	            foreach ($roles as $role) {
	                $role_r = Role::where('id', '=', $role)->firstOrFail();
	                $user->assignRole($role_r); //Assigning role to user
	            }

	        }
	        $user->update($attributes);
	        \DB::commit();
        }catch(\Exception $e){
    		\DB::rollBack();
    		abort(400, '内部错误');
    	}
        return new UserResource($user);
    }


}
