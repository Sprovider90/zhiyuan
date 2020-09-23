<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Api\RoleRequest;

class RoleController extends Controller {


    public function index(RoleRequest $request) {
        $roles = Role::orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);// 获取所有角色
        //RoleResource::wrap('data');
        return RoleResource::collection($roles);
    }
    public function store(RoleRequest $request) {

        $name = $request['name'];
        $type = $request['type'];
        $role = new Role();
        $role->name = $name;
        $role->type = $type;

        $permissions = explode(',', $request['permissions']);
        \DB::beginTransaction();
        try{
        	$role->save();
	        foreach ($permissions as $permission) {
	            $p = Permission::where('id', '=', $permission)->firstOrFail();
	            // 获取新创建的角色并分配权限
	            $role = Role::where('name', '=', $name)->first();
	            $role->givePermissionTo($p);
	        }
	        \DB::commit();
    	}catch(\Exception $e){
    		\DB::rollBack();
    		abort(400, $e->getMessage());
    	}
        return new RoleResource($role);
    }

	public function update(RoleRequest $request, $id) {

        $role = Role::findOrFail($id);

        $input = $request->except(['permissions']);

        \DB::beginTransaction();
        try{
	        $role->fill($input)->save();
	        if(isset($request['permissions'])){
	        	$permissions = explode(',', $request['permissions']);
	        	$p_all = Permission::all();
		        foreach ($p_all as $p) {
		            $role->revokePermissionTo($p);
		        }
		        foreach ($permissions as $permission) {
		            $p = Permission::where('id', '=', $permission)->firstOrFail(); //从数据库中获取相应权限
		            $role->givePermissionTo($p);  // 分配权限到角色
		        }
	        }

	        \DB::commit();
        }catch(\Exception $e){
    		\DB::rollBack();
    		abort(400, $e->getMessage());
    	}

        return new RoleResource($role);
    }
}
