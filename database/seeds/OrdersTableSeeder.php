<?php

use Faker\Generator;
use Illuminate\Database\Seeder;
use App\Models\Orders;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_ids = \App\Models\Customers::all()->pluck('id')->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $orders = factory(Orders::class)
            ->times(10)
            ->make()
            ->each(function ($data, $index) use ($customer_ids, $faker)
            {
                $data->cid = $faker->randomElement($customer_ids);
            });
        // 将数据集合转换为数组，并插入到数据库中
        Orders::insert($orders->toArray());
    }
}
