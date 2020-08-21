<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('phone')->unique();
            $table->string('truename');
            $table->string('password');
            $table->integer('type')->unsigned()->default(1)->comment('账号类型,1是数据中心2是客户平台');
            $table->integer('customer_id')->unsigned()->index()->nullable()->comment('所属客户');
            $table->integer('status')->unsigned()->unsigned()->default(1)->comment('账号状态1正常0禁用');
            $table->integer('img')->nullable()->comment('头像');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
