<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->comment('项目编号');
            $table->string('name')->comment('项目名称');
            $table->integer('customer_id')->comment('客户id');
            $table->string('address')->nullable()->comment('项目地址');
            $table->string('hcho')->comment('甲醛');
            $table->string('tvoc')->comment('TVOS');
            $table->tinyInteger('status')->default(0)->comment('状态0未开始1暂停中2已结束3项目错误4施工中5交付中6维护中7项目大阶段错误');
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
        Schema::dropIfExists('projects');
    }
}
