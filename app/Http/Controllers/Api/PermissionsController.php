<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;
// 引入 laravel-permission 模型
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::all(); // 获取所有权限
        PermissionResource::wrap('data');
        return PermissionResource::collection($permissions);
    }
    public function userIndex(Request $request)
    {
        $permissions = $request->user()->getAllPermissions();
        PermissionResource::wrap('data');
        return PermissionResource::collection($permissions);
    }
    public function roleIndex(Request $request,$id)
    {
        $role = Role::findOrFail($id);
   		$permissions = $role->getAllPermissions();
       
        PermissionResource::wrap('data');
        return PermissionResource::collection($permissions);
    }
     
}