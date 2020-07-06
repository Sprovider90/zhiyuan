<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('good_id')->default(1)->comment('产品id 默认1');
            $table->string('number')->comment('设备编号');
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
        Schema::dropIfExists('orders_devices');
    }
}
