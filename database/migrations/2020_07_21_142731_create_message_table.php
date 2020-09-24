<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type')->default(1)->comment('消息类型 1系统消息 2预警消息');
            $table->string('content')->comment('消息内容');
            $table->text('rev_users')->comment('接收的账号ids');
            $table->integer('user_id')->comment('接收的账号id');
            $table->string('url')->nullable()->comment('跳转地址');
            $table->tinyInteger('is_read')->default(0)->comment('0未读1已读');
            $table->timestampTz('send_time',0)->nullable()->comment('消息触发的时候时间');
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
        Schema::dropIfExists('message');
    }
}
