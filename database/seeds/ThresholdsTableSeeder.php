<?php

use Illuminate\Database\Seeder;
use App\Models\Thresholds;

class ThresholdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Thresholds = factory(Thresholds::class)
            ->times(10)
            ->make();


        // 将数据集合转换为数组，并插入到数据库中
        Thresholds::insert($Thresholds->toArray());
    }
}
