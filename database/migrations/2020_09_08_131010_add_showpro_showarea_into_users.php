<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowproShowareaIntoUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users',function (Blueprint $table) {
            $table->integer('show_project_id')->after('img')->default(0)->comment('默认显示项目');
            $table->integer('show_area_id')->after('show_project_id')->default(0)->comment('默认显示区域');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('show_project_id');
            $table->dropColumn('show_area_id');
        });
    }
}
