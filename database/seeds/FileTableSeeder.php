<?php

use Illuminate\Database\Seeder;
use \App\Models\Files;

class FileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = factory(Files::class)
            ->times(10)
            ->make();


        // 将数据集合转换为数组，并插入到数据库中
        Files::insert($file->toArray());
    }
}
