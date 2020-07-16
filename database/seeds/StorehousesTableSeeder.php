<?php

use Illuminate\Database\Seeder;

class StorehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = factory(\App\Models\Storehouses::class)
            ->times(100)
            ->make();


        // 将数据集合转换为数组，并插入到数据库中
        \App\Models\Storehouses::insert($store->toArray());
    }
}
