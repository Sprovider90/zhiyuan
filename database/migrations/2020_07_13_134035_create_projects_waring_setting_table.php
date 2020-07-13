<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsWaringSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_waring_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->unsigned()->default(0)->comment('项目id');
            $table->integer('remind_time')->unsigned()->default(0)->comment('提醒时间（以分钟为单位）');
            $table->float('percentage', 8, 2)->comment('预警提醒百分比（以小数储存）');
            $table->integer('notice_start_time')->unsigned()->comment('允许通知的开始时间（XXXX格式如900）');
            $table->integer('notice_end_time')->unsigned()->comment('允许通知的结束时间（XXXX格式如1500）');
            $table->string('notice_phone')->comment("通知的手机号（多个逗号隔开）");
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
        Schema::dropIfExists('projects_waring_setting');
    }
}
