<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dictory;

class SeedDictoryData extends Migration
{
    public function up()
    {
        // 清除缓存
//        app()['cache']->forget('spatie.permission.cache');
//        $arr=[1=>"值1",2=>"值2",3=>"值3"];
//        $value_jsons=json_encode($arr);
//        // 先创建权限
//        Dictory::create(['name' => 'xailai1',"value"=>$value_jsons]);
        
    }

    public function down()
    {
        // 清除缓存
//        app()['cache']->forget('spatie.permission.cache');
//
//        Model::unguard();
//        DB::table($tableNames['dictory'])->delete();
//        Model::reguard();
    }
}