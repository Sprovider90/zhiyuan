<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ThresholdsTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        //$this->call(ProjectsAreasTableSeeder::class);
        $this->call(ProjectsStagesTableSeeder::class);
        //生成订单
        $this->call(OrdersTableSeeder::class);
        //生成订单发货设备信息
        $this->call(OrdersDevicesTableSeeder::class);
    }
}
