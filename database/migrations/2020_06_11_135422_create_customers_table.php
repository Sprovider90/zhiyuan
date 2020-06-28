<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * 客户表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name')->comment('公司名称');
            $table->string('company_addr')->nullable()->comment('公司简称');
            /*$table->string('contact')->comment('联系人');
            $table->string('contact_number')->comment('联系电话');*/
            $table->integer('type')->default(0)->comment('客户类型');
            $table->integer('logo')->nullable()->comment('logo');
            $table->string('email')->nullable()->comment('邮箱');
            $table->string('address')->nullable()->comment('地址');
            $table->text('memo')->nullable()->comment('备注');
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
        Schema::dropIfExists('customers');
    }
}
