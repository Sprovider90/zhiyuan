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

//        $arr=[1=>"值1",2=>"值2",3=>"值3"];
//        $value_jsons=json_encode($arr);
//        // 先创建权限
//        Dictory::create(['name' => 'xailai1',"value"=>$value_jsons]);

    }

    public function down()
    {

//        Model::unguard();
//        DB::table('dictory')->delete();
//        Model::reguard();
    }
}
