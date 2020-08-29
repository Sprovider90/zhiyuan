<?php

use Illuminate\Database\Seeder;
use App\Models\Storehouses;
use App\Models\Customers;
use App\Models\Device;
class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_ids = Customers::all()->pluck('id')->toArray();
        $Storehouses_ids =Storehouses::all()->pluck("id")->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $devices = factory(Device::class)
            ->times(100)
            ->make()
            ->each(function ($device, $index)
            use ($customer_ids, $Storehouses_ids,$faker)
            {
                $device->customer_id = $faker->randomElement($customer_ids);
                $device->storehouse_id = $faker->randomElement($Storehouses_ids);

            });

        // 将数据集合转换为数组，并插入到数据库中
        Device::insert($devices->toArray());
    }
}
