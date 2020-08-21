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
            $table->string('name');
            $table->tinyInteger('status')->default(1)->comment('状态1启用0禁用');
            $table->string('thresholdinfo')->default("")->comment('指标阀值,json数据如：{
                "formaldehyde":"0.04~0.06",
                "TVOC":"0.04~0.06"
            }');
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
        Schema::dropIfExists('thresholds');
    }
}
