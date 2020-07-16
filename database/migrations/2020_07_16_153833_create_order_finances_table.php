<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderFinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_finances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->comment('订单ID');
            $table->decimal('money',9,2)->comment('金额');
            $table->tinyInteger('type')->default(1)->comment('类型 1收款 2退款 默认1收款');
            $table->tinyInteger('pay_type')->comment('支付类型： 1对公转账 2微信 3支付宝 4现金 5其他');
            $table->string('file')->nullable()->comment('凭证 多个英文逗号隔开 例如:1,2');
//            $table->date()
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
        Schema::dropIfExists('order_finances');
    }
}
