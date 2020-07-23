<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissionsData extends Migration
{
    public function up()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 先创建权限
        Permission::create(['name' => 'kehuguanli-chakan',"guard_name"=>"web"]);
        Permission::create(['name' => 'kehuguanli-xinzeng',"guard_name"=>"web"]);
        Permission::create(['name' => 'kehuguanli-xiugai',"guard_name"=>"web"]);
        Permission::create(['name' => 'kehuguanli-shanchu',"guard_name"=>"web"]);
        Permission::create(['name' => 'message_list',"guard_name"=>"web"]);

        // 创建站长角色，并赋予权限
        $founder = Role::create(['name' => '至源管理员',"guard_name"=>"web"]);
        $founder->givePermissionTo('kehuguanli-chakan');
        $founder->givePermissionTo('kehuguanli-xinzeng');
        $founder->givePermissionTo('kehuguanli-xiugai');
        $founder->givePermissionTo('kehuguanli-shanchu');
        $founder->givePermissionTo('message_list');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => '至源项目经理',"guard_name"=>"web"]);
        $maintainer->givePermissionTo('kehuguanli-chakan');
    }

    public function down()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}