<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonenoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phonenotice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('warnigs_id')->unsigned()->default(0)->comment('预警列表id');
            $table->string('projectsetting_kz_json',500)->comment('项目通知设置快照');
            $table->tinyInteger('is_send')->default(0)->comment('是否发送通知，1是0否');
            $table->tinyInteger('no_send_reason')->default(0)->comment("不发送通知的原因:0无1没有设置项目的通知2不在允许通知的时间范围3提醒频率超出4预警提醒百分比不满足");
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
        Schema::dropIfExists('phonenotice');
    }
}
