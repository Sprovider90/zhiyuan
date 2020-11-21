<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectInfoToProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->string('project_manage_name')->nullable()->comment('项目负责人');
            $table->string('project_manage_tel')->nullable()->comment('项目负责人电话');
            $table->string('device_manage_name')->nullable()->comment('设备管理人');
            $table->string('device_manage_tel')->nullable()->comment('设备管理电话');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->dropColumn('project_manage_name');
            $table->dropColumn('project_manage_tel');
            $table->dropColumn('device_manage_name');
            $table->dropColumn('device_manage_tel');
        });
    }
}
