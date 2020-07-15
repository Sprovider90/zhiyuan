<?php

use Illuminate\Database\Seeder;
use App\Models\Customers;
use App\Models\Projects;
class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_ids = Customers::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $projects = factory(Projects::class)
            ->times(100)
            ->make()
            ->each(function ($topic, $index)
            use ($customer_ids, $faker)
            {
                $topic->customer_id = $faker->randomElement($customer_ids);

            });

        // 将数据集合转换为数组，并插入到数据库中
        Projects::insert($projects->toArray());
    }
}
