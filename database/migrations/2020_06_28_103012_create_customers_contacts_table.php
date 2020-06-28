<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cid')->comment('客户ID');
            $table->string('contact')->comment('联系人');
            $table->string('contact_phone')->comment('联系电话');
            $table->string('job')->comment('联系人职务');
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
        Schema::dropIfExists('customers_contacts');
    }
}
