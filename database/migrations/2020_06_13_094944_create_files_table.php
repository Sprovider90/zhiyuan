<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('文件名称');
            $table->string('upload_name')->comment('文件上传时名称');
            $table->string('size',20)->comment('文件大小 KB');
            $table->string('ext',50)->comment('文件扩展');
            $table->string('path')->comment('文件路径');
            $table->string('mime',50)->comment('文件mimeType');
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
        Schema::dropIfExists('files');
    }
}
