<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UserRequest;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
    	\DB::beginTransaction();
    	try{
	    	$user = User::create($request->only('phone', 'name', 'password'));
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
    		abort(400, '内部错误');
    	}

        return new UserResource($user);
    }

}
