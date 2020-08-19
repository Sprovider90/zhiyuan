<?php


use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dictory;

class SeedDictoryData extends Migration
{
    public function up()
    {
        // 清除缓存

        $suoshuxinghao_arr=[["id"=>1,"name"=>"所属型号1"],["id"=>2,"name"=>"所属型号2"],["id"=>3,"name"=>"所属型号3"]];
        $arr=["name"=>"所属型号","nameflag"=>"suoshuxinghao","value"=>json_encode($suoshuxinghao_arr)];
        Dictory::create($arr);

        $suoshuxinghao_arr=[
            ["id"=>1,"name"=>"甲醛"],
            ["id"=>2,"name"=>"TVOC"],
            ["id"=>3,"name"=>"PM2.5"],
            ["id"=>4,"name"=>"CO2"],
            ["id"=>5,"name"=>"温度"],
            ["id"=>6,"name"=>"湿度"]];
        $arr=["name"=>"检测数据","nameflag"=>"jianceshuju","value"=>json_encode($suoshuxinghao_arr)];
        Dictory::create($arr);

        $suoshuxinghao_arr=[["id"=>1,"name"=>"客户类型1"],["id"=>2,"name"=>"客户类型2"],["id"=>3,"name"=>"客户类型3"]];
        $arr=["name"=>"客户类型","nameflag"=>"kehuleixin","value"=>json_encode($suoshuxinghao_arr)];
        Dictory::create($arr);



    }

    public function down()
    {

        Model::unguard();
        DB::table('dictories')->delete();
        Model::reguard();
    }
}
