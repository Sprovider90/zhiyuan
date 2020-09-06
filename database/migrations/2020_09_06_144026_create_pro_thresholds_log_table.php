<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProThresholdsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_thresholds_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->unsigned()->default(0)->comment('项目id');
            $table->integer('stage_id')->unsigned()->default(0)->comment('阶段id');
            $table->string('thresholds_name')->comment("标准检测点名称");
            $table->string('fromwhere')->comment("数据来源");
            $table->string('thresholdinfo')->comment('指标阀值,json数据如：{
                "formaldehyde":"0.04~0.06",
            "TVOC":"0.04~0.06"
            }');
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
        Schema::dropIfExists('pro_thresholds_log');
    }
}
