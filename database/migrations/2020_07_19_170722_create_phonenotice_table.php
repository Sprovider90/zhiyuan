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
            $table->integer('project_id')->unsigned()->default(0)->comment('项目id');
            $table->integer('point_id')->unsigned()->default(0)->comment('检测点id');
            $table->integer('warnigs_id')->unsigned()->unique()->default(0)->comment('预警列表id');
            $table->text('projectsetting_kz_json')->nullable()->comment('项目通知设置快照');
            $table->tinyInteger('is_send')->default(0)->comment('是否发送通知，1是0否');
            $table->string('no_send_reason',30)->nullable()->comment("不发送通知的原因:1项目没有设置预警或者设置得不完整（阻断流程）
            2预警列表源数据错误（阻断流程）
            3不在允许通知的时间范围
            4提醒频率超出
            5预警提醒百分比不满足
            6发送时短信为空
            7发送时项目名为空
            8发送时监测点为空
            9发送时某次发送阿里反馈有问题，详情查看日志");
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
