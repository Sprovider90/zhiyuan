<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRssiToDevie extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            //
            $table->string('rssi')->nullable()->comment('设备信号强度');
            $table->tinyInteger('state')->default(1)->comment('设备状态 默认1  1开启 2关闭');
            $table->string('loca_x_y')->nullable()->comment('设备位置坐标 x,y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            //
            $table->dropColumn('rssi');
            $table->dropColumn('state');
            $table->dropColumn('loca_x_y');
        });
    }
}
