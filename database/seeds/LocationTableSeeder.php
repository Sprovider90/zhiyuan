<?php

use Illuminate\Database\Seeder;
use App\Models\ProjectsAreas;
use App\Models\Location;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $area_ids = ProjectsAreas::all()->pluck('id')->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $location = factory(Location::class)
            ->times(5000)
            ->make()
            ->each(function ($data, $index)
            use ($area_ids, $faker)
            {
                $data->area_id = $faker->randomElement($area_ids);
            });

        // 将数据集合转换为数组，并插入到数据库中
        Location::insert($location->toArray());
    }
}
