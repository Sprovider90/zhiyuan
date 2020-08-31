<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreinstallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preinstalls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->comment('项目ID');
            $table->integer('customer_id')->comment('客户ID');
            $table->date('date')->nullable()->comment('日期');
            $table->string('hcho')->nullable()->comment('甲醛');
            $table->string('tvoc')->nullable()->comment('TVOC');
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
        Schema::dropIfExists('preinstalls');
    }
}
