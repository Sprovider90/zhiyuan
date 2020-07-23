<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breakdowns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->comment('项目ID');
            $table->string('device_id')->nullable()->comment('设备id');
            $table->tinyInteger('type')->default(1)->comment('消息类型 1数据丢失 2数据异常');
            $table->timestampTz('happen_time',0)->nullable()->comment('数据故障时间');
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
        Schema::dropIfExists('breakdowns');
    }
}
