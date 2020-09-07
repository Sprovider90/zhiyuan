<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarnigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warnigs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->unsigned()->default(0)->comment('项目id');
            $table->integer('point_id')->unsigned()->default(0)->comment('检测id');
            $table->string('thresholds_name')->comment("标准检测点名称");
            $table->timestampTz('waring_time',0)->nullable();
            $table->string('threshold_keys')->comment("预警指标（多个逗号隔开），如果有值代表在预警列表中暂时");
            $table->string('check_result')->comment("检测结果，用于检测点，区域打空气质量标签");
            $table->string('original_file')->comment("源数据文件所在位置");
            $table->text('originaldata')->comment("预警原始数据,定位问题使用");
            $table->unique(["project_id","point_id","waring_time"]);
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
        Schema::dropIfExists('warnigs');
    }
}
