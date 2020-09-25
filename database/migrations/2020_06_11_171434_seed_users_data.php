<?php


use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class SeedUsersData extends Migration
{
    public function up()
    {
        $arr=[
            'name' => "guoqingkuaile",
            'phone'=>'17780510690',
            'remember_token' => Str::random(10),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'truename'=>"chaoguan",
            'type'=>1,
            'customer_id'=>0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            ];

        User::insert($arr);
    }

    public function down()
    {
        Model::unguard();
        DB::table('users')->delete();
        Model::reguard();
    }
}
