<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarnigsSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warnigs_sms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('warnig_id')->comment('预警id');
            $table->integer('send_id')->comment('发送者账号id');
            $table->integer('customer_id')->nullable()->comment('客户id');
            $table->text('content')->comment('内容');
            $table->string('pics')->nullable()->comment('图片');
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
        Schema::dropIfExists('warnigs_sms');
    }
}
