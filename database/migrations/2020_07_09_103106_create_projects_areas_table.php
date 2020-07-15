<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->comment('项目id');
            $table->string('area_name')->comment('区域名称');
            $table->integer('file')->default(0)->comment('区域图纸id');
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
        Schema::dropIfExists('projects_areas');
    }
}
