<?php

use Illuminate\Database\Seeder;
use App\Models\Customers;
class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $customers = factory(Customers::class)
            ->times(10)
            ->make();


        // 将数据集合转换为数组，并插入到数据库中
        Customers::insert($customers->toArray());
    }
}
