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
        Permission::create(['name' => '项目管理-项目列表-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-项目列表-编辑',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-项目列表-监测点管理',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-项目列表-查看',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-标准阈值管理-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-标准阈值管理-阈值设置',"guard_name"=>"api"]);

        Permission::create(['name' => '项目管理-项目预警设置-阈值设置',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-项目预警设置-预警条件',"guard_name"=>"api"]);

        Permission::create(['name' => '项目管理-预警警报-发送消息',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-预警警报-查看',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-解决方案-回复',"guard_name"=>"api"]);
        Permission::create(['name' => '项目管理-解决方案-查看',"guard_name"=>"api"]);

        Permission::create(['name' => '预评估管理-预评估设置-设置',"guard_name"=>"api"]);
        Permission::create(['name' => '客户管理-客户列表-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '客户管理-客户列表-编辑',"guard_name"=>"api"]);
        Permission::create(['name' => '客户管理-客户列表-查看',"guard_name"=>"api"]);
//        Permission::create(['name' => '客户管理-订单管理-新增',"guard_name"=>"api"]);
//        Permission::create(['name' => '客户管理-订单管理-编辑',"guard_name"=>"api"]);
//        Permission::create(['name' => '客户管理-订单管理-查看',"guard_name"=>"api"]);
//        Permission::create(['name' => '客户管理-订单管理-取消',"guard_name"=>"api"]);
//        Permission::create(['name' => '客户管理-订单管理-发货',"guard_name"=>"api"]);
        Permission::create(['name' => '订单管理-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '订单管理-编辑',"guard_name"=>"api"]);
        Permission::create(['name' => '订单管理-查看',"guard_name"=>"api"]);
        Permission::create(['name' => '订单管理-取消',"guard_name"=>"api"]);
        Permission::create(['name' => '订单管理-发货',"guard_name"=>"api"]);

        Permission::create(['name' => '库存管理-仓库管理-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '库存管理-仓库管理-编辑',"guard_name"=>"api"]);

        Permission::create(['name' => '库存管理-设备管理-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '库存管理-设备管理-批量导入/下载模板',"guard_name"=>"api"]);
        Permission::create(['name' => '库存管理-设备管理-编辑',"guard_name"=>"api"]);
        Permission::create(['name' => '库存管理-设备管理-收回',"guard_name"=>"api"]);
        Permission::create(['name' => '财务管理-财务管理-查看',"guard_name"=>"api"]);
        Permission::create(['name' => '财务管理-财务管理-收款',"guard_name"=>"api"]);
        Permission::create(['name' => '财务管理-财务管理-退款',"guard_name"=>"api"]);
        Permission::create(['name' => '数据报表-数据查询-查看',"guard_name"=>"api"]);
        Permission::create(['name' => '数据报表-数据分析-数据对比',"guard_name"=>"api"]);
        Permission::create(['name' => '数据报表-数据分析-查看',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-用户管理-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-用户管理-编辑',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-用户管理-启用/停用',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-角色管理-新增',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-角色管理-编辑',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-故障排查-列表',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-数据字典-编辑',"guard_name"=>"api"]);
        Permission::create(['name' => '系统管理-登录日志-列表',"guard_name"=>"api"]);


        // 创建站长角色，并赋予权限
//        $founder = Role::create(['name' => '至源管理员',"guard_name"=>"api"]);
//        $founder->givePermissionTo('kehuguanli-chakan');
//        $founder->givePermissionTo('kehuguanli-xinzeng');
//        $founder->givePermissionTo('kehuguanli-xiugai');
//        $founder->givePermissionTo('kehuguanli-shanchu');
//        $founder->givePermissionTo('message-list');
//
//        // 创建管理员角色，并赋予权限
//        $maintainer = Role::create(['name' => '至源项目经理',"guard_name"=>"api"]);
//        $maintainer->givePermissionTo('kehuguanli-chakan');
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
