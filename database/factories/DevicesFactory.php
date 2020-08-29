<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Device::class, function (Faker $faker) {

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);


    return [
        'good_id' => 1,
        'device_number'=>$faker->ean13(),
        'come_date'=>"2020-12-12",
        'model'=>"1",
        'manufacturer'=>"北京",
        'status'=>[1,2,3][rand(0,2)],
        'run_status' => [1,2][rand(0,1)],
        'store_status' => [1,2][rand(0,1)],
        'check_data'=>"1,2,3,4,5,6",
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});

