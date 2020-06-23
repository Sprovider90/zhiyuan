<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_login_log', function (Blueprint $table) {
            //
            $table->bigIncrements('id');
            $table->integer('users_id')->unsigned()->index()->comment('用户id');
            $table->string('ip');
            $table->string('notice')->comment('异常提示');
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
        Schema::dropIfExists('users_login_log');
    }
}
