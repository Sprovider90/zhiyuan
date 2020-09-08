<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerredNocustomerredToWarnigsSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warnigs_sms', function (Blueprint $table) {
            $table->tinyInteger('customerred')->default(0)->comment('状态1已读0未读');
            $table->tinyInteger('nocustomerred')->default(0)->comment('状态1已读0未读');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warnigs_sms', function (Blueprint $table) {
            $table->dropColumn('customerred');
            $table->dropColumn('nocustomerred');
        });
    }
}
