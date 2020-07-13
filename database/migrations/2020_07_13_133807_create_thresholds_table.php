<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThresholdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thresholds', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->tinyInteger('status')->comment('状态1启用0禁用');
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
        Schema::dropIfExists('thresholds');
    }
}
