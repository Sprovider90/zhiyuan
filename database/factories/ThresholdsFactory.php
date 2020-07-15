<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Thresholds::class, function (Faker $faker) {

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    $arr=["formaldehyde"=>"0.04~0.06","TVOC"=>"0.04~0.06"];
    return [
        'name' => $faker->name,
        'status' => 1,
        'thresholdinfo'=>json_encode($arr),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
