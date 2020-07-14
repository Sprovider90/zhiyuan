<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->comment('项目id');
            $table->string('stage_name')->comment('阶段名称');
            $table->date('start_date')->comment('开始时间');
            $table->date('end_date')->comment('结束时间');
            $table->integer('threshold_id')->comment('阈值id');
            $table->tinyInteger('stage')->comment('阶段分类 1施工阶段 2交付阶段 3维护阶段');
            $table->tinyInteger('default')->default(0)->comment('默认  0否 1是  默认0  当为1的时候不可删除');
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
        Schema::dropIfExists('projects_stages');
    }
}
