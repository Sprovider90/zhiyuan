<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Projects::class, function (Faker $faker) {

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'number' => "202007150001",
        'name'=>$faker->title,
        'hcho' => 0.5,
        'tvoc'=>0.6,
        'status'=>0,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});

