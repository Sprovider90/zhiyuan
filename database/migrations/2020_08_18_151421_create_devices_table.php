<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('good_id')->default(1)->comment('商品ID');
            $table->string('device_number')->unique()->comment('设备ID');
            $table->date('come_date')->nullable()->comment('出厂日期');
            $table->integer('model')->nullable()->comment('型号');
            $table->string('manufacturer')->nullable()->comment('厂家');
            $table->integer('storehouse_id')->comment('仓库ID');
            $table->integer('customer_id')->nullable()->comment('所属客户');
            $table->string('check_data')->nullable()->comment('检测数据 英文逗号分隔 例如:1,2,3');
            $table->integer('status')->default(1)->comment('设备状态 1正常 2维修 3损坏');
            $table->integer('run_status')->default(2)->comment('运行状态 1运行中 2已停止');
            $table->integer('store_status')->default(1)->comment('出库状态 1未出库 2已出库');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
