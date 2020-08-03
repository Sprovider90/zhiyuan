<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->comment('项目ID');
            $table->string('number')->comment('点位编号');
            $table->string('name')->comment('点位名称');
            $table->integer('good_id')->default(1)->comment('产品id  默认1  1空气净化器');
            $table->integer('device_id')->nullable()->comment('设备id');
            $table->integer('area_id')->comment('该项目下的区域ID');
            $table->tinyInteger('status')->default(1)->comment('状态 1启用 2停用 默认1');
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
        Schema::dropIfExists('projects_positions');
    }
}
