<?php

use Illuminate\Database\Seeder;
use App\Models\ProjectsWaringSetting;
class ProjectsWaringSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $customers = factory(ProjectsWaringSetting::class)
            ->times(1)
            ->make();


        // 将数据集合转换为数组，并插入到数据库中
        ProjectsWaringSetting::insert($customers->toArray());
    }
}
