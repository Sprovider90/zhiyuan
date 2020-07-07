<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->comment('订单编号');
            $table->integer('gid')->default(1)->comment('产品id 默认1 空气检测器');
            $table->integer('cid')->comment('客户id');
            $table->integer('num')->comment('订单数量');
            $table->string('money')->comment('订单金额');
            $table->tinyInteger('order_status')->default(1)->comment('状态 默认1 1待付款 2已付款 3已退款 4已取消');
            $table->tinyInteger('send_goods_status')->default(1)->comment('状态 默认1 1待出库 2已出库');
            $table->string('express_name')->nullable()->comment('快递名称');
            $table->string('express_number')->nullable()->comment('快递编号');
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
        Schema::dropIfExists('orders');
    }
}
