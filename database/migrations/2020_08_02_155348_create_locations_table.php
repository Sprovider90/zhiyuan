<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //关联区域表 一个区域有多个坐标点
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position_id')->unique()->comment('点位id');
            $table->string('left')->comment('坐标 左 百分数');
            $table->string('top')->comment('坐标 顶 百分数');
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
        Schema::dropIfExists('locations');
    }
}
