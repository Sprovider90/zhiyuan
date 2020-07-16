<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CraeteStorehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //仓库表
        Schema::create('storehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('仓库名称');
            $table->string('address')->nullable()->comment('仓库地址');
            $table->string('desc')->nullable()->comment('描述');
            $table->tinyInteger('status')->default(1)->comment('状态 1开启 2关闭');
//            $table->integer('num')->default(0)->comment('设备数');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('storehouses');
    }
}
