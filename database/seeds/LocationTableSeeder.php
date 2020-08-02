<?php

use Illuminate\Database\Seeder;
use App\Models\ProjectsPositions;
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
        $postion_ids = ProjectsPositions::all()->pluck('id')->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $location = factory(Location::class)
            ->times(200)
            ->make()
            ->each(function ($data, $index)
            use ($postion_ids, $faker)
            {
                $data->position_id = $faker->unique()->randomElement($postion_ids);
            });

        // 将数据集合转换为数组，并插入到数据库中
        Location::insert($location->toArray());
    }
}
