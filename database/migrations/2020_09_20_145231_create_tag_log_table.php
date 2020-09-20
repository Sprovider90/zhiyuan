<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('model_id')->unsigned()->default(0)->comment('主体id');
            $table->tinyInteger('model_type')->default(0)->comment('主体模型 1项目 2区域 3监测点');
            $table->tinyInteger('air_quality')->default(0)->comment('空气质量结果 1优质 2合格 3污染');
            $table->string('original_file',255)->nullable()->comment('标签来源，源数据文件所在位置');
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
        Schema::dropIfExists('tag_log');
    }
}
