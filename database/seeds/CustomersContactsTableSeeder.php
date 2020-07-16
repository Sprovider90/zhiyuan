<?php

use Faker\Generator;
use Illuminate\Database\Seeder;

class CustomersContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_ids = \App\Models\Customers::pluck('id')->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $customerContacts = factory(\App\Models\CustomersContacts::class)
            ->times(20)
            ->make()
            ->each(function ($data, $index) use ($customer_ids, $faker)
            {
                $data->cid = $faker->randomElement($customer_ids);
            });
        // 将数据集合转换为数组，并插入到数据库中
        \App\Models\CustomersContacts::insert($customerContacts->toArray());
    }
}
