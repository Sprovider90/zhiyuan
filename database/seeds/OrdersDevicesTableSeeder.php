<?php

use Illuminate\Database\Seeder;

class OrdersDevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order_ids = \App\Models\Orders::where('send_goods_status',2)->pluck('id')->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $ordersDevices = factory(\App\Models\OrdersDevices::class)
            ->times(300)
            ->make()
            ->each(function ($data, $index) use ($order_ids, $faker)
            {
                $order_id = $faker->randomElement($order_ids);
                $data->order_id = $order_id;
                $order = \App\Models\Orders::find($order_id);
//                $data->customer_id = $order->cid;
            });
        // 将数据集合转换为数组，并插入到数据库中
        \App\Models\OrdersDevices::insert($ordersDevices->toArray());
    }
}
