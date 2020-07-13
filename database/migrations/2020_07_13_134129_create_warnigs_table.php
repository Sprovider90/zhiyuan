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
            $table->dateTime('waring_time',0);
            $table->string('threshold_keys')->comment("预警指标（多个逗号隔开）");
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
